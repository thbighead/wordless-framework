<?php

namespace Wordless\Tests\Unit;

use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Abstractions\Pagination\Posts;
use Wordless\Adapters\Post;
use Wordless\Adapters\PostType;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\EmptyTaxonomySubQueryBuilder;
use Wordless\Adapters\QueryBuilder\QueryBuilder;
use Wordless\Tests\Contracts\NeedsTestEnvironment;
use Wordless\Tests\WordlessTestCase;

class PostQueryBuilderTest extends WordlessTestCase
{
    use NeedsTestEnvironment;

    public function testQueryAlreadySetExceptionCannotOccurOnConstructor()
    {
        $query1 = new PostQueryBuilder;
        $query2 = new PostQueryBuilder;
        $query3 = QueryBuilder::fromPostEntity();
        $query4 = QueryBuilder::fromPostEntity(PostType::POST);

        $this->assertInstanceOf(PostQueryBuilder::class, get_class($query1));
        $this->assertInstanceOf(PostQueryBuilder::class, get_class($query2));
        $this->assertInstanceOf(PostQueryBuilder::class, get_class($query3));
        $this->assertInstanceOf(PostQueryBuilder::class, get_class($query4));
    }

    public function testPaginatedQueryResult(): Posts
    {
        $this->assertInstanceOf(Posts::class, $result = (new PostQueryBuilder)->paginate());

        return $result;
    }

    /**
     * @param Posts $result
     * @depends testPaginatedQueryResult
     */
    public function testPaginationFirstPage(Posts $result)
    {
        $this->assertEquals(Posts::FIRST_PAGE, $result->getCurrentPageNumber());
    }

    /**
     * @param Posts $result
     * @depends testPaginatedQueryResult
     */
    public function testPaginationPostsKeyedById(Posts $result)
    {
        foreach ($result->getCurrentPageItems() as $post_id => $post) {
            $this->assertInstanceOf(Post::class, $post);
            $this->assertEquals($post_id, $post->ID ?? null);
        }
    }

    /**
     * @param Posts $result
     * @depends testPaginatedQueryResult
     */
    public function testNextPage(Posts $result)
    {
        $current_page = $result->getCurrentPageNumber();
        $pageOne = $result->getCurrentPageItems();

        $result->nextPage();

        if ($result->getNumberOfPages() > 1) {
            $this->assertEquals(++$current_page, $result->getCurrentPageNumber());
            $this->assertNotEquals($pageOne, $result->getCurrentPageItems());

            return;
        }

        $this->assertEquals(1, $result->getCurrentPageNumber());
        $this->assertEquals($pageOne, $result->getCurrentPageNumber());
    }

    public function testTaxonomySubQueries()
    {
        $query = new PostQueryBuilder;
        $taxonomySubQuery = new EmptyTaxonomySubQueryBuilder;

        $taxonomySubQuery = $taxonomySubQuery->whereTaxonomyIs(
            'testing',
            WpQueryTaxonomy::COLUMN_TERM_ID,
            [1, 2, 5, 48]
        );

        dump('test:', $taxonomySubQuery->build());

        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subQuery) {
            return $subQuery->whereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subSubQuery) {
                return $subSubQuery->whereTaxonomyIn(
                    'chemical_element',
                    WpQueryTaxonomy::COLUMN_SLUG,
                    ['ag', 'o', 'pb'],
                    false
                );
            });
        });

        dump('test:', $taxonomySubQuery->build());

        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subQuery) {
            return $subQuery->whereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subSubQuery) {
                return $subSubQuery->whereTaxonomyNotExists(
                    'chemical_element',
                    WpQueryTaxonomy::COLUMN_TERM_ID,
                    5
                )->andWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subSubSubQuery) {
                    return $subSubSubQuery->whereTaxonomyNotIn(
                        'pokemon',
                        WpQueryTaxonomy::COLUMN_TERM_TAXONOMY_ID,
                        374
                    )->orWhereTaxonomyIn(
                        'testing',
                        WpQueryTaxonomy::COLUMN_NAME,
                        ['A/B Test', 'Math Test']
                    )->orWhereTaxonomyIs('book', WpQueryTaxonomy::COLUMN_SLUG, 'harry-potter');
                });
            });
        });

        dump('test:', $taxonomySubQuery->build());

        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomyIs(
            'chemical_element',
            WpQueryTaxonomy::COLUMN_SLUG,
            'cl'
        );

        dump('test:', $taxonomySubQuery->build());

        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subQuery) {
            return $subQuery->whereTaxonomyExists(
                'pokemon',
                WpQueryTaxonomy::COLUMN_NAME,
                'Bulbasaur'
            )->andWhereTaxonomyNotIn(
                'pokemon_type',
                WpQueryTaxonomy::COLUMN_TERM_TAXONOMY_ID,
                [19, 65, 103]
            );
        });

        dump('test:', $taxonomySubQuery->build());

        $query->whereTaxonomy($taxonomySubQuery);

    }
}

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
            $taxonomy_testing = 'testing',
            WpQueryTaxonomy::COLUMN_TERM_ID,
            $taxonomy_testing_term_ids = [1, 2, 5, 48]
        );

        $this->assertEquals($arguments = [[
            WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_testing,
            WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_TERM_ID,
            WpQueryTaxonomy::KEY_TERMS => $taxonomy_testing_term_ids,
            WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_AND,
            WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
        ]], $taxonomySubQuery->build());

        $taxonomy_chemical_element = 'chemical_element';
        $taxonomy_chemical_element_slugs = ['ag', 'o', 'pb'];
        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subQuery) use (
            $taxonomy_chemical_element,
            $taxonomy_chemical_element_slugs
        ) {
            return $subQuery->whereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subSubQuery) use (
                $taxonomy_chemical_element,
                $taxonomy_chemical_element_slugs
            ) {
                return $subSubQuery->whereTaxonomyIn(
                    $taxonomy_chemical_element,
                    WpQueryTaxonomy::COLUMN_SLUG,
                    $taxonomy_chemical_element_slugs,
                    false
                );
            });
        });

        $arguments[WpQueryTaxonomy::KEY_RELATION] = WpQueryTaxonomy::RELATION_OR;
        $arguments[] = [
            WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_chemical_element,
            WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_SLUG,
            WpQueryTaxonomy::KEY_TERMS => $taxonomy_chemical_element_slugs,
            WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_IN,
            WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => false,
        ];

        $this->assertEquals($arguments, $taxonomySubQuery->build());

        $taxonomy_pokemon = 'pokemon';
        $taxonomy_pokemon_term_taxonomy_id = 374;
        $taxonomy_chemical_element_term_id = 5;
        $taxonomy_testing_names = ['A/B Test', 'Math Test'];
        $taxonomy_book = 'book';
        $taxonomy_book_slug = 'harry-potter';
        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subQuery) use (
            $taxonomy_chemical_element,
            $taxonomy_chemical_element_term_id,
            $taxonomy_pokemon,
            $taxonomy_pokemon_term_taxonomy_id,
            $taxonomy_testing,
            $taxonomy_testing_names,
            $taxonomy_book,
            $taxonomy_book_slug
        ) {
            return $subQuery->whereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subSubQuery) use (
                $taxonomy_chemical_element,
                $taxonomy_chemical_element_term_id,
                $taxonomy_pokemon,
                $taxonomy_pokemon_term_taxonomy_id,
                $taxonomy_testing,
                $taxonomy_testing_names,
                $taxonomy_book,
                $taxonomy_book_slug
            ) {
                return $subSubQuery->whereTaxonomyNotExists(
                    $taxonomy_chemical_element,
                    WpQueryTaxonomy::COLUMN_TERM_ID,
                    $taxonomy_chemical_element_term_id
                )->andWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subSubSubQuery) use (
                    $taxonomy_pokemon,
                    $taxonomy_pokemon_term_taxonomy_id,
                    $taxonomy_testing,
                    $taxonomy_testing_names,
                    $taxonomy_book,
                    $taxonomy_book_slug
                ) {
                    return $subSubSubQuery->whereTaxonomyNotIn(
                        $taxonomy_pokemon,
                        WpQueryTaxonomy::COLUMN_TERM_TAXONOMY_ID,
                        $taxonomy_pokemon_term_taxonomy_id
                    )->orWhereTaxonomyIn(
                        $taxonomy_testing,
                        WpQueryTaxonomy::COLUMN_NAME,
                        $taxonomy_testing_names
                    )->orWhereTaxonomyIs($taxonomy_book, WpQueryTaxonomy::COLUMN_SLUG, $taxonomy_book_slug);
                });
            });
        });

        $arguments[] = [
            WpQueryTaxonomy::KEY_RELATION => WpQueryTaxonomy::RELATION_AND,
            [
                WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_chemical_element,
                WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_TERM_ID,
                WpQueryTaxonomy::KEY_TERMS => $taxonomy_chemical_element_term_id,
                WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_NOT_EXISTS,
                WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
            ],
            [
                WpQueryTaxonomy::KEY_RELATION => WpQueryTaxonomy::RELATION_OR,
                [
                    WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_pokemon,
                    WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_TERM_TAXONOMY_ID,
                    WpQueryTaxonomy::KEY_TERMS => $taxonomy_pokemon_term_taxonomy_id,
                    WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_NOT_IN,
                    WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
                ],
                [
                    WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_testing,
                    WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_NAME,
                    WpQueryTaxonomy::KEY_TERMS => $taxonomy_testing_names,
                    WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_IN,
                    WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
                ],
                [
                    WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_book,
                    WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_SLUG,
                    WpQueryTaxonomy::KEY_TERMS => $taxonomy_book_slug,
                    WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_AND,
                    WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
                ],
            ],
        ];

        $this->assertEquals($arguments, $taxonomySubQuery->build());

        $taxonomy_chemical_element_slug = 'cl';
        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomyIs(
            $taxonomy_chemical_element,
            WpQueryTaxonomy::COLUMN_SLUG,
            $taxonomy_chemical_element_slug
        );

        $arguments[] = [
            WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_chemical_element,
            WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_SLUG,
            WpQueryTaxonomy::KEY_TERMS => $taxonomy_chemical_element_slug,
            WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_AND,
            WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
        ];

        $this->assertEquals($arguments, $taxonomySubQuery->build());

        $taxonomy_pokemon_name = 'Bulbasaur';
        $taxonomy_pokemon_type = 'pokemon_type';
        $taxonomy_pokemon_type_term_taxonomy_ids = [19, 65, 103];
        $taxonomySubQuery = $taxonomySubQuery->orWhereTaxonomy(function (EmptyTaxonomySubQueryBuilder $subQuery) use (
            $taxonomy_pokemon,
            $taxonomy_pokemon_name,
            $taxonomy_pokemon_type,
            $taxonomy_pokemon_type_term_taxonomy_ids
        ) {
            return $subQuery->whereTaxonomyExists(
                $taxonomy_pokemon,
                WpQueryTaxonomy::COLUMN_NAME,
                $taxonomy_pokemon_name
            )->andWhereTaxonomyNotIn(
                $taxonomy_pokemon_type,
                WpQueryTaxonomy::COLUMN_TERM_TAXONOMY_ID,
                $taxonomy_pokemon_type_term_taxonomy_ids
            );
        });

        $arguments[] = [
            WpQueryTaxonomy::KEY_RELATION => WpQueryTaxonomy::RELATION_AND,
            [
                WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_pokemon,
                WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_NAME,
                WpQueryTaxonomy::KEY_TERMS => $taxonomy_pokemon_name,
                WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_EXISTS,
                WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
            ],
            [
                WpQueryTaxonomy::KEY_TAXONOMY => $taxonomy_pokemon_type,
                WpQueryTaxonomy::KEY_COLUMN => WpQueryTaxonomy::COLUMN_TERM_TAXONOMY_ID,
                WpQueryTaxonomy::KEY_TERMS => $taxonomy_pokemon_type_term_taxonomy_ids,
                WpQueryTaxonomy::KEY_OPERATOR => WpQueryTaxonomy::OPERATOR_NOT_IN,
                WpQueryTaxonomy::KEY_INCLUDE_CHILDREN => true,
            ],
        ];

        $this->assertEquals($arguments, $taxonomySubQuery->build());

        $query->whereTaxonomy($taxonomySubQuery);
    }
}

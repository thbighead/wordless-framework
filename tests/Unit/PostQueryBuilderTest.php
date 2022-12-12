<?php

namespace Wordless\Tests\Unit;

use Wordless\Abstractions\Pagination\Posts;
use Wordless\Adapters\Post;
use Wordless\Adapters\PostType;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder;
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
}

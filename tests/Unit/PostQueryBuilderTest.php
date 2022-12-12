<?php

namespace Wordless\Tests\Unit;

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

    public function testPaginatedQueryResult()
    {
        $query = new PostQueryBuilder;
        $query->paginate();
    }
}

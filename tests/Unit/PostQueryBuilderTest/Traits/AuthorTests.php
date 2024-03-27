<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait AuthorTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereAuthorIdQuery(): void
    {
        $authors_ids = [1, 2, 3, 4, 5];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR => $authors_ids[0]]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereAuthorId($authors_ids[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR => implode(',', $authors_ids)]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereAuthorId(...$authors_ids))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereAuthorNiceNameQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR_NICE_NAME => 'author_name_1']),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->whereAuthorNiceName('author_name_1'))
        );
    }
}

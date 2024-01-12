<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait AuthorTest
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereAuthorId($authors_ids[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR => implode(',', $authors_ids)]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereAuthorId(...$authors_ids))
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereAuthorNiceName('author_name_1'))
        );
    }
}

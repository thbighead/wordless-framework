<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait AuthorTest
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereAuthorIdQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR => 1]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereAuthorId(1),
                self::ARGUMENTS_KEY
            )
        );

        $authors_ids = [1, 2, 3, 4, 5];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR => implode(',', $authors_ids)]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereAuthorId($authors_ids),
                self::ARGUMENTS_KEY
            )
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
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereAuthorNiceName('author_name_1'),
                self::ARGUMENTS_KEY
            )
        );
    }
}

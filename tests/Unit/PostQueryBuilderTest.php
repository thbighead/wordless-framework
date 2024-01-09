<?php

namespace Wordless\Tests\Unit;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Tests\WordlessTestCase;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;

class PostQueryBuilderTest extends WordlessTestCase
{
    private const DEFAULT_ARGUMENTS = [
        PostType::QUERY_TYPE_KEY => StandardType::ANY,
        PostQueryBuilder::KEY_IGNORE_STICKY_POSTS => true,
        PostQueryBuilder::KEY_NO_FOUND_ROWS => true,
        PostQueryBuilder::KEY_NO_PAGING => true,
        Posts::KEY_POSTS_PER_PAGE => -1,
        PostsListFormat::FIELDS_KEY => PostsListFormat::all_fields->value,
    ];
    private const ARGUMENTS_KEY = 'arguments';
    private const CUSTOM_POST_TYPES = ['first_type', 'second_type'];

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testEmptyQuery(): void
    {
        $this->assertEquals(
            self::DEFAULT_ARGUMENTS,
            Reflection::getClassPropertyValue(new PostQueryBuilder, self::ARGUMENTS_KEY)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereTypeQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => self::CUSTOM_POST_TYPES[0]]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereType(self::CUSTOM_POST_TYPES[0]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => self::CUSTOM_POST_TYPES]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereType(self::CUSTOM_POST_TYPES),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            self::DEFAULT_ARGUMENTS,
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereType([]),
                self::ARGUMENTS_KEY
            )
        );
    }
}

<?php

namespace Wordless\Tests\Unit;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Tests\Unit\PostQueryBuilderTest\Traits\AuthorTest;
use Wordless\Tests\Unit\PostQueryBuilderTest\Traits\CategoryTest;
use Wordless\Tests\WordlessTestCase;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\Enums\OrderByDirection;
use Wordless\Wordpress\QueryBuilder\Enums\Status;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Exceptions\TrySetEmptyPostType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnParameter;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Exceptions\InvalidOrderByClause;

class PostQueryBuilderTest extends WordlessTestCase
{
//    use AuthorTest, CategoryTest;

    private const DEFAULT_ARGUMENTS = [
        PostType::QUERY_TYPE_KEY => [StandardType::ANY],
        PostQueryBuilder::KEY_IGNORE_STICKY_POSTS => true,
        PostQueryBuilder::KEY_NO_FOUND_ROWS => true,
        PostQueryBuilder::KEY_NO_PAGING => true,
        Posts::KEY_POSTS_PER_PAGE => -1,
        PostsListFormat::FIELDS_KEY => PostsListFormat::all_fields->value,
    ];
    private const ARGUMENTS_KEY = 'arguments';
    private const KEY_ORDER_BY = 'orderby';
    private const KEY_AUTHOR = 'author';
    private const KEY_AUTHOR_NICE_NAME = 'author_name';
    private const CUSTOM_POST_TYPES = ['first_type', 'second_type'];
//
//    /**
//     * @return void
//     * @throws ReflectionException
//     */
//    public function testEmptyQuery(): void
//    {
//        $this->assertEquals(
//            self::DEFAULT_ARGUMENTS,
//            Reflection::getClassPropertyValue(new PostQueryBuilder, self::ARGUMENTS_KEY)
//        );
//    }
//
//    /**
//     * @return void
//     * @throws ReflectionException
//     */
//    public function testWhereTypeQuery(): void
//    {
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => [self::CUSTOM_POST_TYPES[0]]]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereType(self::CUSTOM_POST_TYPES[0]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => self::CUSTOM_POST_TYPES]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereType(...self::CUSTOM_POST_TYPES),
//                self::ARGUMENTS_KEY
//            )
//        );
//    }
//
//    /**
//     * @return void
//     * @throws PostQueryBuilder\Traits\OrderBy\Exceptions\InvalidOrderByClause
//     * @throws ReflectionException
//     */
//    public function testOrderByQuery(): void
//    {
//        $this->assertEquals(
//            array_merge(
//                self::DEFAULT_ARGUMENTS,
//                [self::KEY_ORDER_BY => [ColumnParameter::author->value => OrderByDirection::ascending->value]]
//            ),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->orderBy(ColumnParameter::author),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(
//                self::DEFAULT_ARGUMENTS,
//                [self::KEY_ORDER_BY => [ColumnParameter::author->value => OrderByDirection::descending->value]]
//            ),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->orderBy([ColumnParameter::author->value => OrderByDirection::descending]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(
//                self::DEFAULT_ARGUMENTS,
//                [self::KEY_ORDER_BY => [
//                    ColumnParameter::author->value => OrderByDirection::descending->value,
//                    ColumnParameter::name->value => OrderByDirection::ascending->value,
//                ]]
//            ),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->orderBy([
//                    ColumnParameter::author->value => OrderByDirection::descending,
//                    ColumnParameter::name->value => OrderByDirection::ascending,
//                ]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(
//                self::DEFAULT_ARGUMENTS,
//                [self::KEY_ORDER_BY => [
//                    ColumnParameter::author->value => OrderByDirection::ascending->value,
//                    ColumnParameter::name->value => OrderByDirection::ascending->value,
//                ]]
//            ),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->orderBy([
//                    ColumnParameter::author,
//                    ColumnParameter::name,
//                ]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(
//                self::DEFAULT_ARGUMENTS,
//                [self::KEY_ORDER_BY => [
//                    ColumnParameter::author->value => OrderByDirection::descending->value,
//                    ColumnParameter::name->value => OrderByDirection::descending->value,
//                ]]
//            ),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->orderBy([
//                    ColumnParameter::author,
//                    ColumnParameter::name,
//                ], OrderByDirection::descending),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->expectException(InvalidOrderByClause::class);
//        (new PostQueryBuilder)->orderBy([
//            ColumnParameter::author->value => OrderByDirection::descending,
//            ColumnParameter::name->value => OrderByDirection::ascending,
//            ColumnParameter::date->value,
//        ]);
//    }
//
//    /**
//     * @return void
//     * @throws ReflectionException
//     */
//    public function testWhereIdQuery()
//    {
//        $post_ids = [1, 2, 3, 4, 5];
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, ['p' => $post_ids[0]]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereId($post_ids[0]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, [PostQueryBuilder::KEY_POST_IN => $post_ids]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereId($post_ids),
//                self::ARGUMENTS_KEY
//            )
//        );
//    }
//
//    /**
//     * @return void
//     * @throws ReflectionException
//     */
//    public function testWhereNotIdQuery()
//    {
//        $post_ids = [1, 2, 3, 4];
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => [$post_ids[0]]]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereNotId($post_ids[0]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => $post_ids]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereNotId($post_ids),
//                self::ARGUMENTS_KEY
//            )
//        );
//    }
//
//    /**
//     * @return void
//     * @throws ReflectionException
//     */
//    public function testWhereSlugQuery()
//    {
//        $slugs = ['slug_1', 'slug_2', 'slug_3'];
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, ['name' => $slugs[0]]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereSlug($slugs[0]),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, ['post_name__in' => $slugs]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereSlug($slugs),
//                self::ARGUMENTS_KEY
//            )
//        );
//    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereStatusQuery()
    {
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, [Status::post_status_key->value => Status::any->value]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereStatus(Status::any),
//                self::ARGUMENTS_KEY
//            )
//        );
//
//        $this->assertEquals(
//            array_merge(self::DEFAULT_ARGUMENTS, [
//                Status::post_status_key->value => Status::any->value,
//                PostType::QUERY_TYPE_KEY => [StandardType::attachment->name],
//            ]),
//            Reflection::getClassPropertyValue(
//                (new PostQueryBuilder)->whereStatus(Status::any)->whereType(StandardType::attachment),
//                self::ARGUMENTS_KEY
//            )
//        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [
                Status::post_status_key->value => Status::inherit->value,
                PostType::QUERY_TYPE_KEY => [StandardType::attachment->name],
            ]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereStatus(Status::publish)->whereType(StandardType::attachment),
                self::ARGUMENTS_KEY
            )
        );
    }
}

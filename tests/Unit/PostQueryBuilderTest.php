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
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Exceptions\TrySetEmptyPostType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnReference;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\Direction;
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

        $this->expectException(TrySetEmptyPostType::class);
        (new PostQueryBuilder)->whereType([]);
    }

    /**
     * @return void
     * @throws PostQueryBuilder\Traits\OrderBy\Exceptions\InvalidOrderByClause
     * @throws ReflectionException
     */
    public function testOrderByQuery(): void
    {
        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [ColumnReference::author->value => Direction::ascending->value]]
            ),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->orderBy(ColumnReference::author),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [ColumnReference::author->value => Direction::descending->value]]
            ),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->orderBy([ColumnReference::author->value => Direction::descending]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::descending->value,
                    ColumnReference::name->value => Direction::ascending->value,
                ]]
            ),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->orderBy([
                    ColumnReference::author->value => Direction::descending,
                    ColumnReference::name->value => Direction::ascending,
                ]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::ascending->value,
                    ColumnReference::name->value => Direction::ascending->value,
                ]]
            ),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->orderBy([
                    ColumnReference::author,
                    ColumnReference::name,
                ]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::descending->value,
                    ColumnReference::name->value => Direction::descending->value,
                ]]
            ),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->orderBy([
                    ColumnReference::author,
                    ColumnReference::name,
                ], Direction::descending),
                self::ARGUMENTS_KEY
            )
        );

        $this->expectException(InvalidOrderByClause::class);
        (new PostQueryBuilder)->orderBy([
            ColumnReference::author->value => Direction::descending,
            ColumnReference::name->value => Direction::ascending,
            ColumnReference::date->value,
        ]);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereAuthorIdQuery()
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
    public function testWhereAuthorNiceNameQuery()
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_AUTHOR_NICE_NAME => 'author_name_1']),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereAuthorNiceName('author_name_1'),
                self::ARGUMENTS_KEY
            )
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereCategoryIdQuery()
    {
        $categories_ids = [1, 2, 3];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['cat' => $categories_ids[0]]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereCategoryId($categories_ids[0]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__in' => $categories_ids]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereCategoryId($categories_ids),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__and' => $categories_ids]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereCategoryId($categories_ids, true),
                self::ARGUMENTS_KEY
            )
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereCategoryNameQuery()
    {
        $categories = ['cat1', 'cat2', 'cat3'];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category_name' => $categories[0]]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereCategoryName($categories[0]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category_name' => implode(',', $categories)]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereCategoryName($categories),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category_name' => implode('+', $categories)]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereCategoryName($categories, true),
                self::ARGUMENTS_KEY
            )
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereNotCategoryIdQuery()
    {
        $categories_ids = [1, 2, 3];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['cat' => -$categories_ids[0]]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereNotCategoryId($categories_ids[0]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__not_in' => $categories_ids]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereNotCategoryId($categories_ids),
                self::ARGUMENTS_KEY
            )
        );
    }

    public function testWhereNotIdQuery()
    {
        $post_ids = [1, 2, 3, 4];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => [$post_ids[0]]]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereNotId($post_ids[0]),
                self::ARGUMENTS_KEY
            )
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => $post_ids]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereNotId($post_ids),
                self::ARGUMENTS_KEY
            )
        );
    }
}

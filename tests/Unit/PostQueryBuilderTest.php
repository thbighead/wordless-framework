<?php

namespace Wordless\Tests\Unit;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Tests\Unit\PostQueryBuilderTest\Traits\AuthorTests;
use Wordless\Tests\Unit\PostQueryBuilderTest\Traits\CategoryTests;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnReference;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\Direction;

class PostQueryBuilderTest extends QueryBuilderTestCase
{
    use AuthorTests;
    use CategoryTests;

    private const DEFAULT_ARGUMENTS = [
        PostType::QUERY_TYPE_KEY => StandardType::ANY,
        'post_status' => StandardStatus::REALLY_ANY,
        'ignore_sticky_posts' => true,
        PostQueryBuilder::KEY_NO_FOUND_ROWS => true,
        PostQueryBuilder::KEY_NO_PAGING => true,
        Posts::KEY_POSTS_PER_PAGE => -1,
        PostsListFormat::FIELDS_KEY => PostsListFormat::all_fields->value,
    ];
    public const DUMMY_POST_IDS = [1, 2, 3, 4];
    public const DUMMY_POST_NAMES = ['post1', 'post2', 'post3'];
    private const ARGUMENTS_KEY = 'arguments';
    private const KEY_ORDER_BY = 'orderby';
    private const KEY_AUTHOR = 'author';
    private const KEY_AUTHOR_NICE_NAME = 'author_name';
    private const CUSTOM_POST_TYPES = ['first_type', 'second_type'];

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testEmptyQuery(): void
    {
        $this->assertEquals(
            self::DEFAULT_ARGUMENTS,
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereTypeQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => [self::CUSTOM_POST_TYPES[0]]]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->whereType(self::CUSTOM_POST_TYPES[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => self::CUSTOM_POST_TYPES]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->whereType(...self::CUSTOM_POST_TYPES))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrderByQuery(): void
    {
        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [ColumnReference::author->value => Direction::ascending->value]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderByAscending(ColumnReference::author)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::ascending->value,
                    ColumnReference::slug->value => Direction::ascending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderByAscending(ColumnReference::author, ColumnReference::slug)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [ColumnReference::author->value => Direction::descending->value]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderByDescending(ColumnReference::author)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::descending->value,
                    ColumnReference::slug->value => Direction::descending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderByDescending(ColumnReference::author, ColumnReference::slug)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::descending->value,
                    ColumnReference::slug->value => Direction::ascending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderByDescending(ColumnReference::author)
                ->orderByAscending(ColumnReference::slug)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::ascending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderBy(ColumnReference::author)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::descending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderBy(ColumnReference::author, Direction::descending)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::ascending->value,
                    ColumnReference::slug->value => Direction::descending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderBy(ColumnReference::author)
                ->orderBy(ColumnReference::slug, Direction::descending)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [
                    ColumnReference::author->value => Direction::ascending->value,
                    ColumnReference::slug->value => Direction::descending->value,
                    ColumnReference::date->value => Direction::ascending->value,
                ]]
            ),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->orderBy(ColumnReference::author)
                ->orderBy(ColumnReference::slug, Direction::descending)
                ->orderByAscending(ColumnReference::date)
            )
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereIdQuery()
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [
                'page_id' => self::DUMMY_POST_IDS[0],
                'p' => self::DUMMY_POST_IDS[0],
            ]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereId(self::DUMMY_POST_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__in' => self::DUMMY_POST_IDS]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereId(...self::DUMMY_POST_IDS))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereNotIdQuery()
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => [self::DUMMY_POST_IDS[0]]]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereNotId(self::DUMMY_POST_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => self::DUMMY_POST_IDS]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereNotId(...self::DUMMY_POST_IDS))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereSlugQuery()
    {
        $slugs = ['slug_1', 'slug_2', 'slug_3'];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['pagename' => $slugs[0], 'name' => $slugs[0]]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereSlug($slugs[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post_name__in' => $slugs]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereSlug(...$slugs))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereStatusQuery()
    {
        $key_post_status = Reflection::getNonPublicConstValue(PostQueryBuilder::class, 'KEY_POST_STATUS');

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [$key_post_status => [StandardStatus::ANY]]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)->whereStatus(StandardStatus::ANY))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [
                $key_post_status => [StandardStatus::ANY],
                PostType::QUERY_TYPE_KEY => [StandardType::attachment->name],
            ]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->whereStatus(StandardStatus::ANY)
                ->whereType(StandardType::attachment))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [
                $key_post_status => [StandardStatus::publish->value],
                PostType::QUERY_TYPE_KEY => [StandardType::attachment->name],
            ]),
            $this->buildArgumentsFromQueryBuilder((new PostQueryBuilder)
                ->whereStatus(StandardStatus::publish)
                ->whereType(StandardType::attachment))
        );
    }
}

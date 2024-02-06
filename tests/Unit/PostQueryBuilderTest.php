<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Tests\Unit\PostQueryBuilderTest\Traits\AuthorTest;
use Wordless\Tests\Unit\PostQueryBuilderTest\Traits\CategoryTest;
use Wordless\Tests\WordlessTestCase;
use Wordless\Wordpress\Models\Post\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\ColumnReference;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy\Enums\Direction;

class PostQueryBuilderTest extends WordlessTestCase
{
    use AuthorTest, CategoryTest;

    private const DEFAULT_ARGUMENTS = [
        PostType::QUERY_TYPE_KEY => [StandardType::ANY],
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder))
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereType(self::CUSTOM_POST_TYPES[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [PostType::QUERY_TYPE_KEY => self::CUSTOM_POST_TYPES]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->orderByAscending(ColumnReference::author, ColumnReference::slug)
            )
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_ORDER_BY => [ColumnReference::author->value => Direction::descending->value]]
            ),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->orderByDescending(ColumnReference::author, ColumnReference::slug)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
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
            array_merge(self::DEFAULT_ARGUMENTS, ['p' => self::DUMMY_POST_IDS[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereId(self::DUMMY_POST_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__in' => self::DUMMY_POST_IDS]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereId(...self::DUMMY_POST_IDS))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereNotIdQuery()
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['p' => self::DUMMY_POST_IDS[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereNotId(self::DUMMY_POST_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post__not_in' => self::DUMMY_POST_IDS]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereNotId(...self::DUMMY_POST_IDS))
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
            array_merge(self::DEFAULT_ARGUMENTS, ['name' => $slugs[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereSlug($slugs[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['post_name__in' => $slugs]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereSlug(...$slugs))
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
            array_merge(self::DEFAULT_ARGUMENTS, [$key_post_status => StandardStatus::ANY]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)->whereStatus(StandardStatus::ANY))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [
                $key_post_status => StandardStatus::ANY,
                PostType::QUERY_TYPE_KEY => [StandardType::attachment->name],
            ]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereStatus(StandardStatus::ANY)
                ->whereType(StandardType::attachment))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [
                $key_post_status => StandardStatus::inherit->value,
                PostType::QUERY_TYPE_KEY => [StandardType::attachment->name],
            ]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereStatus(StandardStatus::publish)
                ->whereType(StandardType::attachment))
        );
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return mixed
     * @throws ReflectionException
     */
    public static function getArgumentsFromReflectionQueryBuilder(QueryBuilder $queryBuilder): mixed
    {
        $reflectionClass = Reflection::of($queryBuilder);
        $reflectionClass->callNonPublicMethod('buildArguments');

        return $reflectionClass->getNonPublicPropertyValue(self::ARGUMENTS_KEY);
    }
}

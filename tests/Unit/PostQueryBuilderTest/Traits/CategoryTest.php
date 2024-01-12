<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait CategoryTest
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereCategoryIdQuery(): void
    {
        $categories_ids = [1, 2, 3];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['cat' => $categories_ids[0]]),
            self::getArgumentsFromReflectionPostQueryBuilder((new PostQueryBuilder)
                ->whereCategoryId($categories_ids[0]))
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
    public function testWhereCategoryNameQuery(): void
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
    public function testWhereNotCategoryIdQuery(): void
    {
        $categories_ids = [1, 2, 3];

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['cat' => -$categories_ids[0]]),
            self::getArgumentsFromReflectionPostQueryBuilder((new PostQueryBuilder)
                ->whereNotCategoryId($categories_ids[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__not_in' => $categories_ids]),
            Reflection::getClassPropertyValue(
                (new PostQueryBuilder)->whereNotCategoryId($categories_ids),
                self::ARGUMENTS_KEY
            )
        );
    }
}

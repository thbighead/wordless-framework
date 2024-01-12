<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait CategoryTest
{
    private const DUMMY_CATEGORY_IDS = [1, 2, 3, 4];
    private const DUMMY_CATEGORY_NAMES = ['cat1', 'cat2', 'cat3'];
    private const KEY_CATEGORY_NAME = 'category_name';
    private const KEY_CATEGORY_ID = 'cat';

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereRelatesToAnyCategoryNameQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_CATEGORY_NAME => self::DUMMY_CATEGORY_NAMES[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder())
                ->whereRelatesToAnyCategoryName(self::DUMMY_CATEGORY_NAMES[0]))
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_CATEGORY_NAME => implode(',', self::DUMMY_CATEGORY_NAMES)]
            ),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder())
                ->whereRelatesToAnyCategoryName(...self::DUMMY_CATEGORY_NAMES))
        );
    }


    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereRelatesToAnyCategoryIdQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_CATEGORY_ID => self::DUMMY_CATEGORY_IDS[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereRelatesToAnyCategoryId(self::DUMMY_CATEGORY_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__in' => self::DUMMY_CATEGORY_IDS]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereRelatesToAnyCategoryId(...self::DUMMY_CATEGORY_IDS))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereRelatesToAllCategoryNameQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_CATEGORY_NAME => self::DUMMY_CATEGORY_NAMES[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereRelatesToAllCategoryName(self::DUMMY_CATEGORY_NAMES[0]))
        );

        $this->assertEquals(
            array_merge(
                self::DEFAULT_ARGUMENTS,
                [self::KEY_CATEGORY_NAME => implode('+', self::DUMMY_CATEGORY_NAMES)]
            ),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereRelatesToAllCategoryName(...self::DUMMY_CATEGORY_NAMES))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereRelatesToAllCategoryIdQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_CATEGORY_ID => self::DUMMY_CATEGORY_IDS[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereRelatesToAllCategoryId(self::DUMMY_CATEGORY_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__and' => self::DUMMY_CATEGORY_IDS]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereRelatesToAllCategoryId(...self::DUMMY_CATEGORY_IDS))
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testWhereNotCategoryIdQuery(): void
    {
        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, [self::KEY_CATEGORY_ID => -self::DUMMY_CATEGORY_IDS[0]]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereNotCategoryId(self::DUMMY_CATEGORY_IDS[0]))
        );

        $this->assertEquals(
            array_merge(self::DEFAULT_ARGUMENTS, ['category__not_in' => self::DUMMY_CATEGORY_IDS]),
            self::getArgumentsFromReflectionQueryBuilder((new PostQueryBuilder)
                ->whereNotCategoryId(...self::DUMMY_CATEGORY_IDS))
        );
    }
}

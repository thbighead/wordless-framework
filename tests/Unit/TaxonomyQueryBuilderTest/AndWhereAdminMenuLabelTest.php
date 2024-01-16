<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

class AndWhereAdminMenuLabelTest extends TaxonomyBuilderTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuLabelTest(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereAdminMenuLabel('test_label');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['label' => 'test_label'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuLabelWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereAdminMenuLabel('test_label')
            ->andWhereAdminMenuLabel('test_label');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['label' => 'test_label'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuLabelWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereName('name')
            ->andOnlyDefault()
            ->andWhereAdminMenuLabel('test_label');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'label' => 'test_label',
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuLabelWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereAdminMenuLabel($string);

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['label' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuLabelWhitEmptyStringArgument(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereAdminMenuLabel('');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals([], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }
}

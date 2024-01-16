<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

class AndWhereAdminMenuSingularLabelTest extends TaxonomyBuilderTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuSingularLabelTest(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereAdminMenuSingularLabel('test_label');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['singular_label' => 'test_label'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuSingularLabelWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereAdminMenuSingularLabel('test_label')
            ->andWhereAdminMenuSingularLabel('test_label');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['singular_label' => 'test_label'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuSingularLabelWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereName('name')
            ->andOnlyDefault()
            ->andWhereAdminMenuSingularLabel('test_label');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'singular_label' => 'test_label',
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
    public function testAndWhereAdminMenuSingularLabelWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereAdminMenuSingularLabel($string);

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['singular_label' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereAdminMenuSingularLabelWhitEmptyStringArgument(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereAdminMenuSingularLabel('');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals([], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }
}

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuSingularLabelTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;

trait NotTests
{
    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAdminMenuSingularLabelTest(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notWhereAdminMenuSingularLabel('test_label');

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['singular_label' => 'test_label'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAdminMenuSingularLabelWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereAdminMenuSingularLabel('test_label')
            ->notWhereAdminMenuSingularLabel('test_label');

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['singular_label' => 'test_label'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAdminMenuSingularLabelWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereName('name')
            ->notOnlyDefault()
            ->notWhereAdminMenuSingularLabel('test_label');

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAdminMenuSingularLabelWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notWhereAdminMenuSingularLabel($string);

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['singular_label' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testNotWhereAdminMenuSingularLabelWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::getInstance()->notWhereAdminMenuSingularLabel('');
    }
}

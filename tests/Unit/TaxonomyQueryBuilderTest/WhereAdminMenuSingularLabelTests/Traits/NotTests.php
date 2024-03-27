<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuSingularLabelTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait NotTests
{
    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testNotWhereAdminMenuSingularLabelTest(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereAdminMenuSingularLabel('test_label');

        $this->assertNotOperator($taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereAdminMenuSingularLabel('test_label')
            ->whereAdminMenuSingularLabel('test_label');

        $this->assertNotOperator($taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->whereAdminMenuSingularLabel('test_label');

        $this->assertNotOperator($taxonomyQueryBuilder);

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

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)->whereAdminMenuSingularLabel($string);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(['singular_label' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testNotWhereAdminMenuSingularLabelWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::getInstance(operator: Operator::not)->whereAdminMenuSingularLabel('');
    }
}

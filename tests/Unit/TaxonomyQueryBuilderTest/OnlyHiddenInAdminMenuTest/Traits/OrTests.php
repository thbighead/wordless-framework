<?php

namespace  Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInAdminMenuTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait OrTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyHiddenInAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyHiddenFromAdminMenu();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyHiddenInAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->onlyAvailableInAdminMenu()
            ->onlyHiddenFromAdminMenu();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyHiddenInAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromAdminMenu();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_ui' => false,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

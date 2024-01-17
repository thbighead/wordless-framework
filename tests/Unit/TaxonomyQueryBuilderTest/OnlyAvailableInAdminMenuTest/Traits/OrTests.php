<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

trait OrTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyAvailableInAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->orOnlyAvailableInAdminMenu();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyAvailableInAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orOnlyAvailableInAdminMenu()
            ->orOnlyAvailableInAdminMenu();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyAvailableInAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereName('name')
            ->orOnlyDefault()
            ->orOnlyAvailableInAdminMenu();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_ui' => true,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

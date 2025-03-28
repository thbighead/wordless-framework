<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInAdminMenuTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyHiddenInAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->onlyHiddenFromAdminMenu();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyHiddenInAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->onlyAvailableInAdminMenu()
            ->onlyHiddenFromAdminMenu();

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndOnlyHiddenInAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromAdminMenu();

        $this->assertAndOperator($taxonomyQueryBuilder);

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

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyAvailableInAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notOnlyAvailableInAdminMenu();

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyAvailableInAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notOnlyAvailableInAdminMenu()
            ->notOnlyAvailableInAdminMenu();

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyAvailableInAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereName('name')
            ->notOnlyDefault()
            ->notOnlyAvailableInAdminMenu();

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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

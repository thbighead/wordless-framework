<?php

namespace TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

class NotOnlyHiddenFromAdminMenuTest extends TaxonomyBuilderTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyHiddenFromAdminMenu(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notOnlyHiddenFromAdminMenu();

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyHiddenFromAdminMenuWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andOnlyAvailableInAdminMenu()
            ->andOnlyHiddenFromAdminMenu();

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_ui' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyHiddenFromAdminMenuWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereName('name')
            ->andOnlyDefault()
            ->andOnlyHiddenFromAdminMenu();

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

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

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyCustomTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyCustom(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notOnlyCustom();

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyCustomWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notOnlyDefault()
            ->notOnlyCustom();

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyCustomWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereName('name')
            ->notOnlyCustom();

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'name' => 'name',
                '_builtin' => false,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

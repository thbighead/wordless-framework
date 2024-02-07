<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInTagCloudTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyAvailableInTagCloud(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->onlyAvailableInTagCloud();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_tagcloud' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyAvailableInTagCloudWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->onlyHiddenFromTagCloud()
            ->onlyAvailableInTagCloud();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_tagcloud' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndOnlyAvailableInTagCloudWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->whereName('name')
            ->onlyDefault()
            ->onlyAvailableInTagCloud();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_tagcloud' => true,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

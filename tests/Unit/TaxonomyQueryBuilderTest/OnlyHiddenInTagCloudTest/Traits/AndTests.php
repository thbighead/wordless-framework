<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyHiddenInTagCloud(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->onlyHiddenFromTagCloud();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_tagcloud' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyHiddenInTagCloudWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->onlyAvailableInTagCloud()
            ->onlyHiddenFromTagCloud();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_tagcloud' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndOnlyHiddenInTagCloudWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->whereName('name')
            ->onlyDefault()
            ->onlyHiddenFromTagCloud();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'show_tagcloud' => false,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

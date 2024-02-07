<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInTagCloudTest\Traits;

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
    public function testOrOnlyAvailableInTagCloud(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyAvailableInTagCloud();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['show_tagcloud' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyAvailableInTagCloudWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->onlyHiddenFromTagCloud()
            ->onlyAvailableInTagCloud();

        $this->assertOrOperator($taxonomyQueryBuilder);

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
    public function testOrOnlyAvailableInTagCloudWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->whereName('name')
            ->onlyDefault()
            ->onlyAvailableInTagCloud();

        $this->assertOrOperator($taxonomyQueryBuilder);

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

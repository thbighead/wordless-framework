<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndWhereUrlQueryVariable(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->whereUrlQueryVariable('string_query_var');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['query_var' => 'string_query_var'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereUrlQueryVariableWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereUrlQueryVariable('string_query_var_1')
            ->whereUrlQueryVariable('string_query_var_2');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['query_var' => 'string_query_var_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndWhereUrlQueryVariableWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->onlyDefault()
            ->whereUrlQueryVariable('string_query_var');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'query_var' => 'string_query_var',
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
    public function testAndWhereUrlQueryVariableWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->whereUrlQueryVariable($string);

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(['query_var' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testAndWhereUrlQueryVariableWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::make()->whereUrlQueryVariable('');
    }
}

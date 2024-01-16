<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

class AndWhereUrlQueryVariableTest extends TaxonomyBuilderTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereUrlQueryVariable(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereUrlQueryVariable('string_query_var');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['query_var' => 'string_query_var'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereUrlQueryVariableWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereUrlQueryVariable('string_query_var_1')
            ->andWhereUrlQueryVariable('string_query_var_2');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['query_var' => 'string_query_var_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereUrlQueryVariableWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andOnlyDefault()
            ->andWhereUrlQueryVariable('string_query_var');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

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
     * @throws ReflectionException
     */
    public function testAndWhereUrlQueryVariableWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereUrlQueryVariable($string);

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['query_var' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereUrlQueryVariableWhitEmptyStringArgument(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereUrlQueryVariable('');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals([], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }
}

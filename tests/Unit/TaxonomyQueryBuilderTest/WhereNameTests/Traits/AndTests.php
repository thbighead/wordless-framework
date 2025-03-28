<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereName(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->whereName('name_1');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['name' => 'name_1'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     * @throws ReflectionException
     */
    public function testAndWhereNameWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name_1')
            ->whereName('name_2');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['name' => 'name_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testAndWhereNameWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->onlyDefault()
            ->whereName('name_1');

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'name' => 'name_1',
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
    public function testAndWhereNameWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->whereName($string);

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(['name' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testAndWhereNameWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::make()->whereName('');
    }
}

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

trait OrTests
{
    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrWhereName(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->orWhereName('name_1');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrWhereNameWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereName('name_1')
            ->orWhereName('name_2');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['name' => 'name_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrWhereNameWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orOnlyDefault()
            ->orWhereName('name_1');

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
    public function testOrWhereNameWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->orWhereName($string);

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['name' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testOrWhereNameWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::getInstance()->orWhereName('');
    }
}

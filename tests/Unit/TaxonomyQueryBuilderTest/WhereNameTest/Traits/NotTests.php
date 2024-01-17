<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testNotWhereName(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notWhereName('name_1');

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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
    public function testNotWhereNameWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereName('name_1')
            ->notWhereName('name_2');

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['name' => 'name_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotWhereNameWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notOnlyDefault()
            ->notWhereName('name_1');

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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
    public function testNotWhereNameWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->notWhereName($string);

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['name' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws EmptyStringParameter
     */
    public function testNotWhereNameWhitEmptyStringArgument(): void
    {
        $this->expectException(EmptyStringParameter::class);

        TaxonomyQueryBuilder::getInstance()->notWhereName('');
    }
}

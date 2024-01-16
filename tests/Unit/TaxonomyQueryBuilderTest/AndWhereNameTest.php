<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

class AndWhereNameTest extends TaxonomyBuilderTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereName(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereName('name_1');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['name' => 'name_1'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereNameWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andWhereName('name_1')
            ->andWhereName('name_2');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(
            ['name' => 'name_2'],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereNameWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->andOnlyDefault()
            ->andWhereName('name_1');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

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
     * @throws ReflectionException
     */
    public function testAndWhereNameWhitLargeStringArgument(): void
    {
        $string = str_repeat('a', 256 * 1024);

        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereName($string);

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals(['name' => $string], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndWhereNameWhitEmptyStringArgument(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->andWhereName('');

        $this->assertInstanceOf(AndComparison::class, $taxonomyQueryBuilder);

        $this->assertEquals([], $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder));
    }
}

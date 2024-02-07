<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyCustomTest\Traits;

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
    public function testOrOnlyCustom(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyCustom();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyCustomWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->onlyDefault()
            ->onlyCustom();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyCustomWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->whereName('name')
            ->onlyCustom();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'name' => 'name',
                '_builtin' => false,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

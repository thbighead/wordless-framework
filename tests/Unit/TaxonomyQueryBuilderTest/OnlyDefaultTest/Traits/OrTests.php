<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyDefaultTest\Traits;

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
    public function testOrOnlyDefault(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyDefault();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyDefaultWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->onlyCustom()
            ->onlyDefault();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyDefaultWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->whereName('name')
            ->onlyDefault();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

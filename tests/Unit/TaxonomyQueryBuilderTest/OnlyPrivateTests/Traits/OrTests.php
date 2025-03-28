<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTests\Traits;

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
    public function testOrOnlyPrivate(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::or)->onlyPrivate();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyPrivateWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::or)
            ->onlyPublic()
            ->onlyPrivate();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyPrivateWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::or)
            ->whereName('name')
            ->onlyDefault()
            ->onlyPrivate();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'public' => false,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

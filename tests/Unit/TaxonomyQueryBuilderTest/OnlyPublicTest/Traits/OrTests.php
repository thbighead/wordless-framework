<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTest\Traits;

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
    public function testOrOnlyPublic(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)->onlyPublic();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyPublicWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->onlyPrivate()
            ->onlyPublic();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testOrOnlyPublicWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::or)
            ->whereName('name')
            ->onlyDefault()
            ->onlyPublic();

        $this->assertOrOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'public' => true,
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

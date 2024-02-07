<?php

namespace  Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyPrivate(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)->onlyPrivate();

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotOnlyPrivateWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->onlyPublic()
            ->onlyPrivate();

        $this->assertNotOperator($taxonomyQueryBuilder);

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
    public function testNotOnlyPrivateWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->onlyPrivate();

        $this->assertNotOperator($taxonomyQueryBuilder);

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

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanOnlyBeUsedByTests\Traits;

use ReflectionException;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotWhereCanOnlyBeUsedBy(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereCanOnlyBeUsedBy(ObjectType::post);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['object_type' => [ObjectType::post->name]],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotWhereCanOnlyBeUsedByWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereCanOnlyBeUsedBy(ObjectType::post)
            ->whereCanOnlyBeUsedBy(ObjectType::post);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['object_type' => [ObjectType::post->name]],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotWhereCanOnlyBeUsedByWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereCanOnlyBeUsedBy(ObjectType::post)
            ->whereCanOnlyBeUsedBy(ObjectType::comment);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['object_type' => [ObjectType::comment->name]],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testNotWhereCanOnlyBeUsedByWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->whereCanOnlyBeUsedBy(ObjectType::post);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'object_type' => [ObjectType::post->name],
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

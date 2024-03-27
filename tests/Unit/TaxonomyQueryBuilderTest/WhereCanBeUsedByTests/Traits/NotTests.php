<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTests\Traits;

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
    public function testNotWhereCanBeUsedBy(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereCanBeUsedBy(ObjectType::post);

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
    public function testNotWhereCanBeUsedByWhereSameAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereCanBeUsedBy(ObjectType::post)
            ->whereCanBeUsedBy(ObjectType::post);

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
    public function testNotWhereCanBeUsedByWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereCanBeUsedBy(ObjectType::post)
            ->whereCanBeUsedBy(ObjectType::comment);

        $this->assertNotOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['object_type' => [ObjectType::post->name, ObjectType::comment->name]],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws EmptyStringParameter
     */
    public function testNotWhereCanBeUsedByWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance(operator: Operator::not)
            ->whereName('name')
            ->onlyDefault()
            ->whereCanBeUsedBy(ObjectType::post);

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

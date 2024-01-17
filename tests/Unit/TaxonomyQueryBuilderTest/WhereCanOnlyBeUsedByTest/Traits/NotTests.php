<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanOnlyBeUsedByTest\Traits;

use ReflectionException;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;

trait NotTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNotWhereCanOnlyBeUsedBy(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereCanOnlyBeUsedBy(ObjectType::post);

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereCanOnlyBeUsedBy(ObjectType::post)
            ->notWhereCanOnlyBeUsedBy(ObjectType::post);

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereCanOnlyBeUsedBy(ObjectType::post)
            ->notWhereCanOnlyBeUsedBy(ObjectType::comment);

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->notWhereName('name')
            ->notOnlyDefault()
            ->notWhereCanOnlyBeUsedBy(ObjectType::post);

        $this->assertInstanceOf(NotComparison::class, $taxonomyQueryBuilder);

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

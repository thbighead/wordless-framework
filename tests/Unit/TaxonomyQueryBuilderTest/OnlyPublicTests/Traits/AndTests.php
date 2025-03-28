<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyPublic(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->onlyPublic();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyPublicWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->onlyPrivate()
            ->onlyPublic();

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndOnlyPublicWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name')
            ->onlyDefault()
            ->onlyPublic();

        $this->assertAndOperator($taxonomyQueryBuilder);

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

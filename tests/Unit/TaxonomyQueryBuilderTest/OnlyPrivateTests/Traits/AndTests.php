<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTests\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyPrivate(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()->onlyPrivate();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['public' => false],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyPrivateWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->onlyPublic()
            ->onlyPrivate();

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndOnlyPrivateWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::make()
            ->whereName('name')
            ->onlyDefault()
            ->onlyPrivate();

        $this->assertAndOperator($taxonomyQueryBuilder);

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

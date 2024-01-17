<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

trait OrTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOrOnlyPrivate(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->orOnlyPrivate();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orOnlyPublic()
            ->orOnlyPrivate();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->orWhereName('name')
            ->orOnlyDefault()
            ->orOnlyPrivate();

        $this->assertInstanceOf(OrComparison::class, $taxonomyQueryBuilder);

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

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyDefaultTest\Traits;

use ReflectionException;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;

trait AndTests
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyDefault(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()->onlyDefault();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            ['_builtin' => true],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAndOnlyDefaultWhereAlreadySet(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->onlyCustom()
            ->onlyDefault();

        $this->assertAndOperator($taxonomyQueryBuilder);

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
    public function testAndOnlyDefaultWhitSomeArguments(): void
    {
        $taxonomyQueryBuilder = TaxonomyQueryBuilder::getInstance()
            ->whereName('name')
            ->onlyDefault();

        $this->assertAndOperator($taxonomyQueryBuilder);

        $this->assertEquals(
            [
                'name' => 'name',
                '_builtin' => true,
            ],
            $this->buildArgumentsFromQueryBuilder($taxonomyQueryBuilder)
        );
    }
}

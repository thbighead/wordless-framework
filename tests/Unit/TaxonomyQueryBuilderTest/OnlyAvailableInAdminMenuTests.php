<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTests\Traits\OrTests;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;

trait OnlyAvailableInAdminMenuTests
{
    use AndTests;
    use NotTests;
    use OrTests;

    /**
     * @throws ReflectionException
     */
    public function assertAndOperator(TaxonomyQueryBuilder $taxonomyQueryBuilder): void
    {
        $this->assertOperator($taxonomyQueryBuilder, Operator::and);
    }

    /**
     * @throws ReflectionException
     */
    public function assertNotOperator(TaxonomyQueryBuilder $taxonomyQueryBuilder): void
    {
        $this->assertOperator($taxonomyQueryBuilder, Operator::not);
    }

    /**
     * @throws ReflectionException
     */
    public function assertOrOperator(TaxonomyQueryBuilder $taxonomyQueryBuilder): void
    {
        $this->assertOperator($taxonomyQueryBuilder, Operator::or);
    }

    /**
     * @throws ReflectionException
     */
    private function assertOperator(TaxonomyQueryBuilder $taxonomyQueryBuilder, Operator $operator): void
    {
        $this->assertEquals(
            $operator,
            Reflection::getNonPublicPropertyValue($taxonomyQueryBuilder, 'operator')
        );
    }
}

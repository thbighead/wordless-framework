<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

trait WhereCanBeUsedByTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

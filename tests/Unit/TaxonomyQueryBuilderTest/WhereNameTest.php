<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use ReflectionException;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

trait WhereNameTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

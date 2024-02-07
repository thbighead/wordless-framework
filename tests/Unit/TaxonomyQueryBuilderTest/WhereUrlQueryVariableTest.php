<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;

trait WhereUrlQueryVariableTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

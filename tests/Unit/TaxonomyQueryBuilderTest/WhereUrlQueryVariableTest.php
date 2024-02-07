<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest\Traits\OrTests;

trait WhereUrlQueryVariableTest
{
    use AndTests;
    use NotTests;
    use OrTests;
}

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTests\Traits\OrTests;

trait WhereUrlQueryVariableTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

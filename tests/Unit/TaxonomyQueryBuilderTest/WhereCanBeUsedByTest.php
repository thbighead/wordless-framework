<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest\Traits\OrTests;

trait WhereCanBeUsedByTest
{
    use AndTests;
    use NotTests;
    use OrTests;
}

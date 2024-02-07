<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTests\Traits\OrTests;

trait WhereCanBeUsedByTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

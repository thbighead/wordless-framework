<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTests\Traits\OrTests;

trait OnlyHiddenInRestApiTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTests\Traits\OrTests;

trait OnlyHiddenInTagCloudTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

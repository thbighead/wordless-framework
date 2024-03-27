<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTests\Traits\OrTests;

trait OnlyPrivateTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

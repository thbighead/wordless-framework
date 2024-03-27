<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTests\Traits\OrTests;

trait OnlyPublicTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

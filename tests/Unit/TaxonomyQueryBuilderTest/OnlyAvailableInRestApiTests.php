<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTests\Traits\OrTests;

trait OnlyAvailableInRestApiTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;

class OnlyAvailableInRestApiTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

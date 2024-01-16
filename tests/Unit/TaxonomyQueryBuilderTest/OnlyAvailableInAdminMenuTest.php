<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;

class OnlyAvailableInAdminMenuTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

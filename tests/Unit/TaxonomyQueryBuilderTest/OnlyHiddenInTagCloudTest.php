<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;

trait OnlyHiddenInTagCloudTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

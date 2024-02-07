<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuLabelTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuLabelTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuLabelTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;

trait WhereAdminMenuLabelTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

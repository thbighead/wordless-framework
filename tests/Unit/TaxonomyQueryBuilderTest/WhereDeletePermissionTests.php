<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTests\Traits\OrTests;

trait WhereDeletePermissionTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

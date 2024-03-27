<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTests\Traits\OrTests;

trait WhereManagePermissionTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

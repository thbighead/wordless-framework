<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest\Traits\OrTests;

trait WhereAssignPermissionTest
{
    use AndTests;
    use NotTests;
    use OrTests;
}

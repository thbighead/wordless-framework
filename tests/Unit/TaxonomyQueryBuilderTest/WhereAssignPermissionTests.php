<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTests\Traits\OrTests;

trait WhereAssignPermissionTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

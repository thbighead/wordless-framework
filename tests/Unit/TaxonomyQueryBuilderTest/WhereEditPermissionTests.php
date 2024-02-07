<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereEditPermissionTests\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereEditPermissionTests\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereEditPermissionTests\Traits\OrTests;

trait WhereEditPermissionTests
{
    use AndTests;
    use NotTests;
    use OrTests;
}

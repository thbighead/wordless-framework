<?php

namespace Wordless\Tests\Unit\TaxonomyQueryBuilderTest;

use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest\Traits\AndTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest\Traits\NotTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest\Traits\OrTests;
use Wordless\Tests\WordlessTestCase\TaxonomyBuilderTestCase;

trait WhereAssignPermissionTest extends TaxonomyBuilderTestCase
{
    use AndTests;
    use NotTests;
    use OrTests;
}

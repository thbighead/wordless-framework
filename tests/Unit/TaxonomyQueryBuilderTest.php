<?php

namespace Wordless\Tests\Unit;

use Wordless\Application\Helpers\Reflection;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInTagCloudTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyCustomTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyDefaultTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInAdminMenuTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuLabelTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuSingularLabelTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanOnlyBeUsedByTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereEditPermissionTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTests;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTests;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

class TaxonomyQueryBuilderTest extends QueryBuilderTestCase
{
    use OnlyAvailableInAdminMenuTests;
    use OnlyAvailableInRestApiTests;
    use OnlyAvailableInTagCloudTests;
    use OnlyCustomTests;
    use OnlyDefaultTests;
    use OnlyHiddenInAdminMenuTests;
    use OnlyHiddenInRestApiTests;
    use OnlyHiddenInTagCloudTests;
    use OnlyPrivateTests;
    use OnlyPublicTests;
    use WhereAdminMenuLabelTests;
    use WhereAdminMenuSingularLabelTests;
    use WhereAssignPermissionTests;
    use WhereCanBeUsedByTests;
    use WhereCanOnlyBeUsedByTests;
    use WhereDeletePermissionTests;
    use WhereEditPermissionTests;
    use WhereManagePermissionTests;
    use WhereNameTests;
    use WhereUrlQueryVariableTests;

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNewTaxonomyQueryWithoutSetFormat()
    {
        $this->assertEquals(
            ResultFormat::objects,
            $this->getFormatPropertyFromTaxonomyQueryBuilder(TaxonomyQueryBuilder::getInstance())
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNewTaxonomyQueryWithSetFormat()
    {
        $this->assertEquals(
            ResultFormat::names,
            $this->getFormatPropertyFromTaxonomyQueryBuilder(
                TaxonomyQueryBuilder::getInstance(ResultFormat::names)
            )
        );
    }

    /**
     * @param TaxonomyQueryBuilder $taxonomyQueryBuilder
     * @return mixed
     * @throws ReflectionException
     */
    private function getFormatPropertyFromTaxonomyQueryBuilder(TaxonomyQueryBuilder $taxonomyQueryBuilder): mixed
    {
        return Reflection::getNonPublicPropertyValue($taxonomyQueryBuilder, 'format');
    }
}

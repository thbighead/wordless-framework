<?php

use Wordless\Application\Helpers\Reflection;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInAdminMenuTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInRestApiTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyAvailableInTagCloudTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyCustomTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyDefaultTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInAdminMenuTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInRestApiTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyHiddenInTagCloudTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPrivateTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\OnlyPublicTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuLabelTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAdminMenuSingularLabelTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereAssignPermissionTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanBeUsedByTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereCanOnlyBeUsedByTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereDeletePermissionTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereEditPermissionTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereManagePermissionTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereNameTest;
use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\WhereUrlQueryVariableTest;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

class TaxonomyQueryBuilderTest extends QueryBuilderTestCase
{
    use OnlyAvailableInAdminMenuTest;
    use OnlyAvailableInRestApiTest;
    use OnlyAvailableInTagCloudTest;
    use OnlyCustomTest;
    use OnlyDefaultTest;
    use OnlyHiddenInAdminMenuTest;
    use OnlyHiddenInRestApiTest;
    use OnlyHiddenInTagCloudTest;
    use OnlyPrivateTest;
    use OnlyPublicTest;
    use WhereAdminMenuLabelTest;
    use WhereAdminMenuSingularLabelTest;
    use WhereAssignPermissionTest;
    use WhereCanBeUsedByTest;
    use WhereCanOnlyBeUsedByTest;
    use WhereDeletePermissionTest;
    use WhereEditPermissionTest;
    use WhereManagePermissionTest;
    use WhereNameTest;
    use WhereUrlQueryVariableTest;

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

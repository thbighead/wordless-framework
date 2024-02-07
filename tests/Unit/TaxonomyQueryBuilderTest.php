<?php

use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;
use Wordless\Application\Helpers\Reflection;

class TaxonomyQueryBuilderTest extends QueryBuilderTestCase
{
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

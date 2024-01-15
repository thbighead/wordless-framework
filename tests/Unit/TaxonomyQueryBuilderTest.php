<?php


use Wordless\Tests\Unit\TaxonomyQueryBuilderTest\Traits\AndComparisonTests;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

class TaxonomyQueryBuilderTest extends QueryBuilderTestCase
{
    use AndComparisonTests;

    public function testNewTaxonomyQueryWithoutSetFormat()
    {
        $this->assertEquals(
            ResultFormat::objects,
            TaxonomyQueryBuilder::getInstance()->getResultFormat()
        );
    }

    public function testNewTaxonomyQueryWithSetFormat()
    {
        $this->assertEquals(
            ResultFormat::names,
            TaxonomyQueryBuilder::getInstance(ResultFormat::names)->getResultFormat()
        );
    }
}

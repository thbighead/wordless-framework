<?php

use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

class TaxonomyQueryBuilderTest extends QueryBuilderTestCase
{

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

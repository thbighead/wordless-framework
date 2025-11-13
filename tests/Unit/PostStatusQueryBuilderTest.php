<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\Arr;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\QueryBuilder\Enums\ResultFormat;
use Wordless\Wordpress\QueryBuilder\PostStatusQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostStatusQueryBuilder\Enums\Operator;

class PostStatusQueryBuilderTest extends QueryBuilderTestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testFormat(): void
    {
        $label = 'Scheduled';

        $this->assertEquals(
            Arr::first(get_post_stati(['name' => 'draft'])),
            $this->queryBuilder(ResultFormat::names)->whereSlug(StandardStatus::draft->value)->first()
        );

        $this->assertEquals(
            get_post_stati(['name' => 'draft', 'label' => $label], 'names', 'or'),
            $this->queryBuilder(ResultFormat::names, Operator::or)
                ->whereLabel($label)
                ->whereSlug(StandardStatus::draft->value)
                ->get()
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOr(): void
    {
        $label = 'Scheduled';

        $this->assertEquals(
            get_post_stati(['name' => 'draft', 'label' => $label], 'objects', 'or'),
            $this->queryBuilder(operator: Operator::or)
                ->whereLabel($label)
                ->whereSlug(StandardStatus::draft->value)
                ->get()
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWhereSlug(): void
    {
        $this->assertEquals(
            Arr::first(get_post_stati(['name' => 'draft'], 'objects')),
            $this->queryBuilder()->whereSlug(StandardStatus::draft->value)->first()
        );
    }

    private function queryBuilder(
        ResultFormat $format = ResultFormat::objects,
        Operator     $operator = Operator::and
    ): PostStatusQueryBuilder
    {
        return new PostStatusQueryBuilder($format, $operator);
    }
}

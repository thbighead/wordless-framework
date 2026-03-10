<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

class PostQueryBuilderTest extends QueryBuilderTestCase
{
    private const QUERY_STANDARDS = [
        'post_type' => 'any',
        'post_status' => [
            'any',
            'inherit',
            'trash',
            'auto-draft',
            'pending',
        ],
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
        'nopaging' => true,
        'posts_per_page' => -1,
        'fields' => 'all',
    ];

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    public function testQueryStandards()
    {
        $this->assertBuiltArguments(self::QUERY_STANDARDS, $this->queryBuilder());
    }

    private function queryBuilder(): PostQueryBuilder
    {
        return new PostQueryBuilder;
    }
}

<?php declare(strict_types=1);

namespace Wordless\Tests\WordlessTestCase;

use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Tests\WordlessTestCase;

class QueryBuilderTestCase extends WordlessTestCase
{
    /**
     * @param array $expected
     * @param QueryBuilder $queryBuilder
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    public function assertBuiltArguments(array $expected, QueryBuilder $queryBuilder): void
    {
        $this->assertEquals($expected, $this->buildArgumentsFromQueryBuilder($queryBuilder));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return array
     * @throws ReflectionException
     */
    protected function buildArgumentsFromQueryBuilder(QueryBuilder $queryBuilder): array
    {
        return Reflection::callNonPublicMethod($queryBuilder, 'buildArguments');
    }
}

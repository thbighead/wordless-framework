<?php

namespace Wordless\Tests\WordlessTestCase;

use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Tests\WordlessTestCase;

class QueryBuilderTestCase extends WordlessTestCase
{
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

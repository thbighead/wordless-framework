<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\CannotBuild\Exceptions\TryingToBuildEmptySubQuery;

trait CannotBuild
{
    /**
     * @return array
     * @throws TryingToBuildEmptySubQuery
     */
    final public function build(): array
    {
        throw new TryingToBuildEmptySubQuery(static::class);
    }
}

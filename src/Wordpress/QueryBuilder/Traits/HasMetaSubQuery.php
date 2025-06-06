<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;

trait HasMetaSubQuery
{
    public function whereMeta(MetaSubQueryBuilder $subQuery): static
    {
        $this->arguments[MetaSubQueryBuilder::ARGUMENT_KEY] = $subQuery;

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     * @throws EmptyQueryBuilderArguments
     */
    private function resolveMetaSubQuery(array &$arguments): static
    {
        $metaSubQueryBuilder = $arguments[MetaSubQueryBuilder::ARGUMENT_KEY] ?? null;

        if ($metaSubQueryBuilder instanceof MetaSubQueryBuilder) {
            $arguments[MetaSubQueryBuilder::ARGUMENT_KEY] = $metaSubQueryBuilder->buildArguments();
        }

        return $this;
    }
}

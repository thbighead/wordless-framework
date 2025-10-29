<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder;

trait HasDateSubQuery
{
    public function whereDate(DateSubQueryBuilder $subQuery): static
    {
        $this->arguments[DateSubQueryBuilder::ARGUMENT_KEY] = $subQuery;

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     * @throws EmptyQueryBuilderArguments
     */
    private function resolveDateSubQuery(array &$arguments): static
    {
        $dateSubQueryBuilder = $arguments[DateSubQueryBuilder::ARGUMENT_KEY] ?? null;

        if ($dateSubQueryBuilder instanceof DateSubQueryBuilder) {
            $arguments[DateSubQueryBuilder::ARGUMENT_KEY] = $dateSubQueryBuilder->buildArguments();
        }

        return $this;
    }
}

<?php

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\Enums\Relation;

abstract class RecursiveSubQueryBuilder extends PostSubQueryBuilder
{
    /** @var static[] $subQueries */
    protected array $subQueries = [];

    public static function make(Relation $relation = Relation::and): static
    {
        return new static($relation);
    }

    public function __construct(protected readonly Relation $relation = Relation::and)
    {
    }

    public function whereSubQuery(RecursiveSubQueryBuilder $subQuery): static
    {
        $this->subQueries[] = $subQuery;

        return $this;
    }

    /**
     * @return array<string|int, string|array<string, string|int|bool|array<int, string|int|bool>>>
     */
    protected function buildArguments(): array
    {
        $arguments[Relation::KEY] = $this->relation->value;

        foreach (parent::buildArguments() as $argument) {
            $arguments[] = $argument;
        }

        foreach ($this->subQueries as $subQuery) {
            $arguments[] = $subQuery->buildArguments();
        }

        return $arguments;
    }
}

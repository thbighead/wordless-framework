<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\ArgumentMounter;

class TaxonomySubQueryBuilder extends RecursiveSubQueryBuilder
{
    use ArgumentMounter;

    final public const ARGUMENT_KEY = 'tax_query';

    private function where(
        Field            $termField,
        string|int|array $term,
        ?string          $taxonomy = null,
        Operator         $operator = Operator::in
    ): static
    {
        $this->arguments[] = $this->mountArgument($termField, $term, $taxonomy, $operator, false);

        return $this;
    }

    private function whereIncludingChildren(
        Field            $termField,
        string|int|array $term,
        ?string          $taxonomy = null,
        Operator         $operator = Operator::in
    ): static
    {
        $this->arguments[] = $this->mountArgument($termField, $term, $taxonomy, $operator);

        return $this;
    }
}

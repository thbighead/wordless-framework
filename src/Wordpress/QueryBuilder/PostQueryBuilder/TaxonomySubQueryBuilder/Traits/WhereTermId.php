<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;

trait WhereTermId
{
    public function whereTermIdAnd(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermId(Arr::prepend($term_ids, $term_id), $taxonomy, Operator::and);
    }

    public function whereTermIdAndIncludingChildren(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermIdIncludingChildren(
            Arr::prepend($term_ids, $term_id),
            $taxonomy,
            Operator::and
        );
    }

    public function whereTermIdExists(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermId(Arr::prepend($term_ids, $term_id), $taxonomy, Operator::exists);
    }

    public function whereTermIdExistsIncludingChildren(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermIdIncludingChildren(
            Arr::prepend($term_ids, $term_id),
            $taxonomy,
            Operator::exists
        );
    }

    public function whereTermIdIn(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermId(Arr::prepend($term_ids, $term_id), $taxonomy);
    }

    public function whereTermIdInIncludingChildren(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermIdIncludingChildren(Arr::prepend($term_ids, $term_id), $taxonomy);
    }

    public function whereTermIdNotExists(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermId(Arr::prepend($term_ids, $term_id), $taxonomy, Operator::not_exists);
    }

    public function whereTermIdNotExistsIncludingChildren(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermIdIncludingChildren(
            Arr::prepend($term_ids, $term_id),
            $taxonomy,
            Operator::not_exists
        );
    }

    public function whereTermIdNotIn(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermId(Arr::prepend($term_ids, $term_id), $taxonomy, Operator::not_in);
    }

    public function whereTermIdNotInIncludingChildren(string $taxonomy, int $term_id, int ...$term_ids): static
    {
        return $this->whereTermIdIncludingChildren(
            Arr::prepend($term_ids, $term_id),
            $taxonomy,
            Operator::not_in
        );
    }

    private function whereTermId(
        int|array $term_id,
        string    $taxonomy,
        Operator  $operator = Operator::in
    ): static
    {
        return $this->where(Field::name, $term_id, $taxonomy, $operator);
    }

    private function whereTermIdIncludingChildren(
        int|array $term_id,
        string    $taxonomy,
        Operator  $operator = Operator::in
    ): static
    {
        return $this->whereIncludingChildren(Field::name, $term_id, $taxonomy, $operator);
    }
}

<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;

trait WhereTermTaxonomyId
{
    public function whereTermTaxonomyIdAnd(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyId(Arr::prepend($term_taxonomy_ids, $term_taxonomy_id), Operator::and);
    }

    public function whereTermTaxonomyIdAndIncludingChildren(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyIdIncludingChildren(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::and
        );
    }

    public function whereTermTaxonomyIdExists(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyId(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::exists
        );
    }

    public function whereTermTaxonomyIdExistsIncludingChildren(
        int $term_taxonomy_id,
        int ...$term_taxonomy_ids
    ): static
    {
        return $this->whereTermTaxonomyIdIncludingChildren(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::exists
        );
    }

    public function whereTermTaxonomyIdIn(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyId(Arr::prepend($term_taxonomy_ids, $term_taxonomy_id));
    }

    public function whereTermTaxonomyIdInIncludingChildren(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyIdIncludingChildren(Arr::prepend($term_taxonomy_ids, $term_taxonomy_id));
    }

    public function whereTermTaxonomyIdNotExists(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyId(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::not_exists
        );
    }

    public function whereTermTaxonomyIdNotExistsIncludingChildren(
        int $term_taxonomy_id,
        int ...$term_taxonomy_ids
    ): static
    {
        return $this->whereTermTaxonomyIdIncludingChildren(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::not_exists
        );
    }

    public function whereTermTaxonomyIdNotIn(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyId(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::not_in
        );
    }

    public function whereTermTaxonomyIdNotInIncludingChildren(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        return $this->whereTermTaxonomyIdIncludingChildren(
            Arr::prepend($term_taxonomy_ids, $term_taxonomy_id),
            Operator::not_in
        );
    }

    /**
     * @param int|int[] $term_taxonomy_id
     * @param Operator $operator
     * @return $this
     */
    private function whereTermTaxonomyId(int|array $term_taxonomy_id, Operator $operator = Operator::in): static
    {
        return $this->where(Field::term_taxonomy_id, $term_taxonomy_id, operator: $operator);
    }

    /**
     * @param int|int[] $term_taxonomy_id
     * @param Operator $operator
     * @return $this
     */
    private function whereTermTaxonomyIdIncludingChildren(
        int|array $term_taxonomy_id,
        Operator  $operator = Operator::in
    ): static
    {
        return $this->whereIncludingChildren(Field::term_taxonomy_id, $term_taxonomy_id, operator: $operator);
    }
}

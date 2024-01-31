<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;

trait WhereTermName
{
    public function whereTermNameAnd(string $taxonomy, string $term_name, string ...$term_names): static
    {
        return $this->whereTermName(Arr::prepend($term_names, $term_name), $taxonomy, Operator::and);
    }

    public function whereTermNameAndIncludingChildren(
        string $taxonomy,
        string $term_name,
        string ...$term_names
    ): static
    {
        return $this->whereTermNameIncludingChildren(
            Arr::prepend($term_names, $term_name),
            $taxonomy,
            Operator::and
        );
    }

    public function whereTermNameExists(string $taxonomy, string $term_name, string ...$term_names): static
    {
        return $this->whereTermName(Arr::prepend($term_names, $term_name), $taxonomy, Operator::exists);
    }

    public function whereTermNameExistsIncludingChildren(
        string $taxonomy,
        string $term_name,
        string ...$term_names
    ): static
    {
        return $this->whereTermNameIncludingChildren(
            Arr::prepend($term_names, $term_name),
            $taxonomy,
            Operator::exists
        );
    }

    public function whereTermNameIn(string $taxonomy, string $term_name, string ...$term_names): static
    {
        return $this->whereTermName(Arr::prepend($term_names, $term_name), $taxonomy);
    }

    public function whereTermNameInIncludingChildren(
        string $taxonomy,
        string $term_name,
        string ...$term_names
    ): static
    {
        return $this->whereTermNameIncludingChildren(Arr::prepend($term_names, $term_name), $taxonomy);
    }

    public function whereTermNameNotExists(string $taxonomy, string $term_name, string ...$term_names): static
    {
        return $this->whereTermName(Arr::prepend($term_names, $term_name), $taxonomy, Operator::not_exists);
    }

    public function whereTermNameNotExistsIncludingChildren(
        string $taxonomy,
        string $term_name,
        string ...$term_names
    ): static
    {
        return $this->whereTermNameIncludingChildren(
            Arr::prepend($term_names, $term_name),
            $taxonomy,
            Operator::not_exists
        );
    }

    public function whereTermNameNotIn(string $taxonomy, string $term_name, string ...$term_names): static
    {
        return $this->whereTermName(Arr::prepend($term_names, $term_name), $taxonomy, Operator::not_in);
    }

    public function whereTermNameNotInIncludingChildren(
        string $taxonomy,
        string $term_name,
        string ...$term_names
    ): static
    {
        return $this->whereTermNameIncludingChildren(
            Arr::prepend($term_names, $term_name),
            $taxonomy,
            Operator::not_in
        );
    }

    private function whereTermName(
        string|array $term_name,
        string       $taxonomy,
        Operator     $operator = Operator::in
    ): static
    {
        return $this->where(Field::name, $term_name, $taxonomy, $operator);
    }

    private function whereTermNameIncludingChildren(
        string|array $term_name,
        string       $taxonomy,
        Operator     $operator = Operator::in
    ): static
    {
        return $this->whereIncludingChildren(Field::name, $term_name, $taxonomy, $operator);
    }
}

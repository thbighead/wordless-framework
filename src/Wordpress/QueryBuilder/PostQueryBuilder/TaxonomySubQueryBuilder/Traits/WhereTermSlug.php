<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;

trait WhereTermSlug
{
    public function whereTermSlugAnd(string $taxonomy, string $term_slug, string ...$term_slugs): static
    {
        return $this->whereTermSlug(Arr::prepend($term_slugs, $term_slug), $taxonomy, Operator::and);
    }

    public function whereTermSlugAndIncludingChildren(
        string $taxonomy,
        string $term_slug,
        string ...$term_slugs
    ): static
    {
        return $this->whereTermSlugIncludingChildren(
            Arr::prepend($term_slugs, $term_slug),
            $taxonomy,
            Operator::and
        );
    }

    public function whereTermSlugExists(string $taxonomy, string $term_slug, string ...$term_slugs): static
    {
        return $this->whereTermSlug(Arr::prepend($term_slugs, $term_slug), $taxonomy, Operator::exists);
    }

    public function whereTermSlugExistsIncludingChildren(
        string $taxonomy,
        string $term_slug,
        string ...$term_slugs
    ): static
    {
        return $this->whereTermSlugIncludingChildren(
            Arr::prepend($term_slugs, $term_slug),
            $taxonomy,
            Operator::exists
        );
    }

    public function whereTermSlugIn(string $taxonomy, string $term_slug, string ...$term_slugs): static
    {
        return $this->whereTermSlug(Arr::prepend($term_slugs, $term_slug), $taxonomy);
    }

    public function whereTermSlugInIncludingChildren(
        string $taxonomy,
        string $term_slug,
        string ...$term_slugs
    ): static
    {
        return $this->whereTermSlugIncludingChildren(Arr::prepend($term_slugs, $term_slug), $taxonomy);
    }

    public function whereTermSlugNotExists(string $taxonomy, string $term_slug, string ...$term_slugs): static
    {
        return $this->whereTermSlug(Arr::prepend($term_slugs, $term_slug), $taxonomy, Operator::not_exists);
    }

    public function whereTermSlugNotExistsIncludingChildren(
        string $taxonomy,
        string $term_slug,
        string ...$term_slugs
    ): static
    {
        return $this->whereTermSlugIncludingChildren(
            Arr::prepend($term_slugs, $term_slug),
            $taxonomy,
            Operator::not_exists
        );
    }

    public function whereTermSlugNotIn(string $taxonomy, string $term_slug, string ...$term_slugs): static
    {
        return $this->whereTermSlug(Arr::prepend($term_slugs, $term_slug), $taxonomy, Operator::not_in);
    }

    public function whereTermSlugNotInIncludingChildren(
        string $taxonomy,
        string $term_slug,
        string ...$term_slugs
    ): static
    {
        return $this->whereTermSlugIncludingChildren(
            Arr::prepend($term_slugs, $term_slug),
            $taxonomy,
            Operator::not_in
        );
    }

    private function whereTermSlug(
        string|array $slug,
        string       $taxonomy,
        Operator     $operator = Operator::in
    ): static
    {
        return $this->where(Field::name, $slug, $taxonomy, $operator);
    }

    private function whereTermSlugIncludingChildren(
        string|array $slug,
        string       $taxonomy,
        Operator     $operator = Operator::in
    ): static
    {
        return $this->whereIncludingChildren(Field::name, $slug, $taxonomy, $operator);
    }
}

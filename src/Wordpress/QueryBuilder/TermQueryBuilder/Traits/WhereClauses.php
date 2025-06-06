<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait WhereClauses
{
    private const NAME_KEY = 'name';
    private const SLUG_KEY = 'slug';
    private const TERM_TAXONOMY_ID_KEY = 'term_taxonomy_id';

    public function whereAnythingLike(string $search_word): static
    {
        $this->arguments['search'] = trim($search_word, '%');

        return $this;
    }

    public function whereDescriptionLike(string $description_like_criteria): static
    {
        $this->arguments['description__like'] = $description_like_criteria;

        return $this;
    }

    public function whereName(string $term_name): static
    {
        $this->arguments[self::NAME_KEY] = $term_name;

        return $this;
    }

    public function whereNameIn(string $term_name, string ...$term_names): static
    {
        $this->arguments[self::NAME_KEY] = Arr::prepend($term_names, $term_name);

        return $this;
    }

    public function whereNameLike(string $name_like_criteria): static
    {
        $this->arguments['name__like'] = $name_like_criteria;

        return $this;
    }

    public function whereParentId(int $parent_id): static
    {
        $this->arguments['parent'] = $parent_id;

        return $this;
    }

    public function whereTermTaxonomyId(int $term_taxonomy_id): static
    {
        $this->arguments[self::TERM_TAXONOMY_ID_KEY] = $term_taxonomy_id;

        return $this;
    }

    public function whereTermTaxonomyIdIn(int $term_taxonomy_id, int ...$term_taxonomy_ids): static
    {
        $this->arguments[self::TERM_TAXONOMY_ID_KEY] = Arr::prepend($term_taxonomy_ids, $term_taxonomy_id);

        return $this;
    }

    public function whereSlug(string $term_slug): static
    {
        $this->arguments[self::SLUG_KEY] = $term_slug;

        return $this;
    }

    public function whereSlugIn(string $term_slug, string ...$term_slugs): static
    {
        $this->arguments[self::SLUG_KEY] = Arr::prepend($term_slugs, $term_slug);

        return $this;
    }
}

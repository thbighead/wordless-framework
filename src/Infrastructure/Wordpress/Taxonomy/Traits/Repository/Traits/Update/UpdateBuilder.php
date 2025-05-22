<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Update;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Update\UpdateBuilder\Exceptions\FailedToUpdateTaxonomyTerm;
use WP_Error;
use WP_Term;

class UpdateBuilder
{
    /** @var array<string, string|int> $arguments */
    private array $arguments = [];

    public function __construct(
        private readonly int    $term_id,
        private readonly string $taxonomy_key
    )
    {
    }

    public function aliasOfTerm(string|Taxonomy|WP_Term $term_slug): static
    {
        if (!is_string($term_slug)) {
            $term_slug = $term_slug->slug;
        }

        $this->arguments['alias_of'] = $term_slug;

        return $this;
    }

    public function description(string $description): static
    {
        $this->arguments['description'] = $description;

        return $this;
    }

    public function parent(int|Taxonomy|WP_Term $parent_id): static
    {
        if (!is_int($parent_id)) {
            $parent_id = $parent_id->term_id;
        }

        $this->arguments['parent'] = $parent_id;

        return $this;
    }

    public function slug(string $new_slug): static
    {
        $this->arguments['slug'] = $new_slug;

        return $this;
    }

    /**
     * @return void
     * @throws FailedToUpdateTaxonomyTerm
     */
    public function update(): void
    {
        if (($result = wp_update_term($this->term_id, $this->taxonomy_key, $this->arguments)) instanceof WP_Error) {
            throw new FailedToUpdateTaxonomyTerm($result);
        }
    }
}

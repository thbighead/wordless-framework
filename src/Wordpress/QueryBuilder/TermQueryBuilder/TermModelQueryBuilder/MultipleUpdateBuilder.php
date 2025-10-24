<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update\UpdateBuilder;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update\UpdateBuilder\Exceptions\FailedToUpdateTaxonomyTerm;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\MultipleUpdateBuilder\Exceptions\CannotUpdateMultipleTermsWithSameSlug;

class MultipleUpdateBuilder extends UpdateBuilder
{
    private readonly bool $can_update_slug;
    private readonly bool $use_same_name_to_all;
    /** @var Taxonomy[] $terms */
    private readonly array $terms;

    /**
     * @param TermModelQueryBuilder $queryBuilder
     * @param string $taxonomy_key
     * @throws EmptyQueryBuilderArguments
     */
    public function __construct(private readonly TermModelQueryBuilder $queryBuilder, string $taxonomy_key)
    {
        parent::__construct(-1, $taxonomy_key);

        $this->can_update_slug = count($this->terms = $this->queryBuilder->get()) <= 1;
    }

    public function name(string $new_name): static
    {
        if (!isset($this->use_same_name_to_all)) {
            $this->use_same_name_to_all = true;
        }

        return parent::name($new_name);
    }

    /**
     * @param string $new_slug
     * @return $this
     * @throws CannotUpdateMultipleTermsWithSameSlug
     */
    public function slug(string $new_slug): static
    {
        if ($this->can_update_slug) {
            parent::slug($new_slug);
        }

        throw new CannotUpdateMultipleTermsWithSameSlug($this->terms, $new_slug);
    }

    /**
     * @return int[]
     * @throws FailedToUpdateTaxonomyTerm
     */
    public function update(): array
    {
        $terms_ids = [];

        if (!isset($this->use_same_name_to_all)) {
            $this->use_same_name_to_all = false;
        }

        foreach ($this->terms as $term) {
            if (!$this->use_same_name_to_all) {
                $this->name($term->name);
            }

            $this->callWpUpdateTerm($terms_ids[] = $term->id());
        }

        return $terms_ids;
    }
}

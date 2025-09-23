<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\Traits\Terms\Exceptions\FailedAggregatingTerm;
use Wordless\Wordpress\Models\Traits\Terms\Exceptions\FailedDisaggregatingTerm;
use WP_Error;

trait Terms
{
    /**
     * @param Taxonomy $term
     * @param Taxonomy ...$terms
     * @return $this
     * @throws FailedAggregatingTerm
     */
    public function appendTerms(Taxonomy $term, Taxonomy ...$terms): static
    {
        foreach ($this->mountTermIds(Arr::prepend($terms, $term)) as $taxonomy => $ids) {
            $result = wp_set_object_terms($this->id(), array_values($ids), $taxonomy, true);

            if ($result instanceof WP_Error || $result === false) {
                throw new FailedAggregatingTerm($this, $taxonomy, $result);
            }
        }

        return $this;
    }

    /**
     * @param string $taxonomy
     * @return $this
     * @throws FailedAggregatingTerm
     */
    public function removeAllTaxonomyTerms(string $taxonomy): static
    {
        $result = wp_set_object_terms($this->id(), [], $taxonomy);

        if ($result instanceof WP_Error || $result === false) {
            throw new FailedAggregatingTerm($this, $taxonomy, $result);
        }

        return $this;
    }

    /**
     * @param Taxonomy $term
     * @param Taxonomy ...$terms
     * @return $this
     * @throws FailedDisaggregatingTerm
     */
    public function removeTerms(Taxonomy $term, Taxonomy ...$terms): static
    {
        foreach ($this->mountTermIds(Arr::prepend($terms, $term)) as $taxonomy => $ids) {
            $result = wp_remove_object_terms($this->id(), array_values($ids), $taxonomy);

            if ($result instanceof WP_Error || $result === false) {
                throw new FailedDisaggregatingTerm($this, $taxonomy, $result);
            }
        }

        return $this;
    }

    /**
     * @param Taxonomy $term
     * @param Taxonomy ...$terms
     * @return $this
     * @throws FailedAggregatingTerm
     */
    public function setTerms(Taxonomy $term, Taxonomy ...$terms): static
    {
        foreach ($this->mountTermIds(Arr::prepend($terms, $term)) as $taxonomy => $ids) {
            $result = wp_set_object_terms($this->id(), array_values($ids), $taxonomy);

            if ($result instanceof WP_Error || $result === false) {
                throw new FailedAggregatingTerm($this, $taxonomy, $result);
            }
        }

        return $this;
    }

    /**
     * @param Taxonomy[] $terms
     * @return array<string, array<int, int>>
     */
    private function mountTermIds(array $terms): array
    {
        $term_ids = [];

        foreach ($terms as $term) {
            $term_ids[$term->taxonomy][$term->id()] = $term->id();
        }

        return $term_ids;
    }
}

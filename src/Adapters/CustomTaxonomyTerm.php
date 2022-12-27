<?php

namespace Wordless\Adapters;

use Wordless\Abstractions\Enums\MetaType;
use Wordless\Contracts\Adapter\RelatedMetaData;
use Wordless\Contracts\Adapter\WithAcfs;
use Wordless\Contracts\Adapter\WithMetaData;
use WP_Term;

class CustomTaxonomyTerm implements RelatedMetaData
{
    use WithAcfs, WithMetaData;

    protected Taxonomy $taxonomy;
    private WP_Term $wpTaxonomyTerm;

    public static function objectType(): string
    {
        return MetaType::TERM;
    }

    /**
     * @param WP_Term|int $taxonomyTerm
     */
    public function __construct($taxonomyTerm, bool $with_acfs = true)
    {
        $this->wpTaxonomyTerm = $taxonomyTerm instanceof WP_Term ? $taxonomyTerm : get_term($taxonomyTerm);
        $this->taxonomy = Taxonomy::find($this->wpTaxonomyTerm->taxonomy);

        if ($with_acfs) {
            $this->loadTaxonomyAcfs($this->wpTaxonomyTerm->term_id);
        }
    }

    public function asWpTerm(): WP_Term
    {
        return $this->wpTaxonomyTerm;
    }

    public function getTaxonomy(): Taxonomy
    {
        return $this->taxonomy;
    }

    private function loadTaxonomyAcfs(int $term_id)
    {
        $this->loadAcfs("{$this->taxonomy->name}_$term_id");
    }
}

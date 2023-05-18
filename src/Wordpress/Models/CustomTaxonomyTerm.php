<?php

namespace Wordless\Wordpress\Models;

use Wordless\Enums\MetaType;
use Wordless\Infrastructure\Http\RelatedMetaData;
use Wordless\Infrastructure\Taxonomy;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use Wordless\Wordpress\Models\Traits\WithMetaData;
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

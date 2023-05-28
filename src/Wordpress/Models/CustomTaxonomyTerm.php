<?php

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use WP_Term;

class CustomTaxonomyTerm implements IRelatedMetaData
{
    use WithAcfs, WithMetaData;

    protected Taxonomy $taxonomy;
    private WP_Term $wpTaxonomyTerm;

    public static function objectType(): MetableObjectType
    {
        return MetableObjectType::term;
    }

    public function __construct(WP_Term|int $taxonomyTerm, bool $with_acfs = true)
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

    private function loadTaxonomyAcfs(int $term_id): void
    {
        $this->loadAcfs("{$this->taxonomy->name}_$term_id");
    }
}

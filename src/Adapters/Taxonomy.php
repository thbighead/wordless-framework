<?php

namespace Wordless\Adapters;

use Wordless\Contracts\Adapter\WithAcfs;
use Wordless\Exceptions\TaxonomyNotRegistered;
use WP_Taxonomy;

/**
 * @mixin WP_Taxonomy
 */
class Taxonomy
{
    use WithAcfs;

    private WP_Taxonomy $wpTaxonomy;

    public static function getAllCustom(): array
    {
        $customTaxonomies = [];

        foreach (get_taxonomies(['_builtin' => false]) as $custom_taxonomy_key) {
            try {
                $customTaxonomies[] = new static($custom_taxonomy_key);
            } catch (TaxonomyNotRegistered $exception) {
                continue;
            }
        }

        return $customTaxonomies;
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpTaxonomy->$method_name(...$arguments);
    }

    /**
     * @param WP_Taxonomy|string $taxonomy
     * @param bool $with_acfs
     */
    public function __construct($taxonomy, bool $with_acfs = true)
    {
        $this->wpTaxonomy = $taxonomy instanceof WP_Taxonomy ? $taxonomy : get_taxonomy($taxonomy);

        if ($with_acfs) {
            $this->loadAcfs($this->wpTaxonomy->);
        }
    }

    public function __get(string $attribute)
    {
        return $this->wpTaxonomy->$attribute;
    }

    public function asWpTaxonomy(): WP_Taxonomy
    {
        return $this->wpTaxonomy;
    }
}

<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Taxonomy\Exceptions\TaxonomyNotRegistered;
use WP_Taxonomy;

class Taxonomy
{
    public static function all(): array
    {
        $customTaxonomies = [];

        foreach (get_taxonomies(output: 'objects') as $wpTaxonomy) {
                $customTaxonomies[] = $wpTaxonomy;
        }

        return $customTaxonomies;
    }
    
    public static function allCustom(): array
    {
    }

    /**
     * @param string $taxonomy
     * @return WP_Taxonomy
     * @throws TaxonomyNotRegistered
     */
    public static function get(string $taxonomy): WP_Taxonomy
    {
        $wpTaxonomy = get_taxonomy($taxonomy);
        
        if (!($wpTaxonomy instanceof WP_Taxonomy)) {
            throw new TaxonomyNotRegistered($taxonomy);
        }
        
        return $wpTaxonomy;
    }

    public static function search()
    {
        
    }
}

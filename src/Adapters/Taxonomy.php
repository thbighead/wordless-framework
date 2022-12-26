<?php

namespace Wordless\Adapters;

use Wordless\Contracts\Adapter\WithAcfs;
use WP_Taxonomy;

/**
 * @mixin WP_Taxonomy
 */
class Taxonomy
{
    use WithAcfs;

    private WP_Taxonomy $wpTaxonomy;

    public static function getAllCustom()
    {
        $customPostTypes = [];

        foreach (get_post_types(['_builtin' => false]) as $custom_post_type_key) {
            try {
                $customPostTypes[] = new static($custom_post_type_key);
            } catch (PostTypeNotRegistered $exception) {
                continue;
            }
        }

        return $customPostTypes;
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpTaxonomy->$method_name(...$arguments);
    }

    /**
     * @param WP_Taxonomy|int $taxonomy
     * @param bool $with_acfs
     */
    public function __construct($taxonomy, bool $with_acfs = true)
    {
        $this->wpTaxonomy = $taxonomy instanceof WP_Taxonomy ? $taxonomy : get_post($taxonomy);

        if ($with_acfs) {
            $this->loadAcfs($this->wpTaxonomy->ID);
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

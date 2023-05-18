<?php

namespace Wordless\Infrastructure;

use Wordless\Application\Guessers\CustomTaxonomyNameGuesser;
use Wordless\Exceptions\TaxonomyNotRegistered;
use Wordless\Infrastructure\Taxonomy\Traits\Register;
use Wordless\Infrastructure\Taxonomy\Traits\Repository;
use Wordless\Wordpress\TaxonomyTermsList;
use WP_Taxonomy;

/**
 * @mixin WP_Taxonomy
 */
abstract class Taxonomy
{
    use Register, Repository;

    /** @var array<static, string> $taxonomies_name_keys */
    protected static array $taxonomies_name_keys = [];
    /** @var static[] $taxonomies */
    private static array $taxonomies = [];
    /** @var TaxonomyTermsList[] $taxonomyTerms */
    private static array $taxonomyTerms = [];

    public const TAXONOMY_NAME_MAX_LENGTH = 32;
    protected const NAME = null;

    private WP_Taxonomy $wpTaxonomy;

    private static function getNameKey()
    {
        return self::$taxonomies_name_keys[static::class] ??
            self::$taxonomies_name_keys[static::class] = static::NAME ??
                (new CustomTaxonomyNameGuesser(static::class))->getValue();
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpTaxonomy->$method_name(...$arguments);
    }

    /**
     * @param WP_Taxonomy|string $taxonomy
     * @throws TaxonomyNotRegistered
     */
    public function __construct($taxonomy)
    {
        if ($taxonomy instanceof WP_Taxonomy) {
            $this->wpTaxonomy = $taxonomy;

            return;
        }

        if (($this->wpTaxonomy = get_taxonomy($taxonomy) ?: null) === null) {
            throw new TaxonomyNotRegistered($taxonomy);
        }
    }

    public function __get(string $attribute)
    {
        return $this->wpTaxonomy->$attribute;
    }
}

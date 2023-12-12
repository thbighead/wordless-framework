<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy;

use Wordless\Application\Guessers\CustomTaxonomyNameGuesser;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\TaxonomyNotRegistered;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Repository;
use Wordless\Wordpress\TaxonomyTermsList;
use WP_Taxonomy;
use WP_Term;

abstract class CustomTaxonomy extends Taxonomy
{
    use Register;
    use Repository;

    /** @var array<static, string> $taxonomies_name_keys */
    protected static array $taxonomies_name_keys = [];
    /** @var static[] $taxonomies */
    private static array $taxonomies = [];
    /** @var TaxonomyTermsList[] $taxonomyTerms */
    private static array $taxonomyTerms = [];

    public const TAXONOMY_NAME_MAX_LENGTH = 32;
    protected const NAME_KEY = null;

    /**
     * @return void
     * @throws TaxonomyNotRegistered
     */
    protected function setWpTaxonomy(): void
    {
        if (!(($taxonomy = get_taxonomy($this->taxonomy)) instanceof WP_Taxonomy)) {
            throw new TaxonomyNotRegistered($taxonomy);
        }

        $this->wpTaxonomy = $taxonomy;
    }

    private static function getNameKey()
    {
        return self::$taxonomies_name_keys[static::class] ??
            self::$taxonomies_name_keys[static::class] = static::NAME_KEY ??
                (new CustomTaxonomyNameGuesser(static::class))->getValue();
    }
}

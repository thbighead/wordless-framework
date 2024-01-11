<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Wordpress\Models\Tag\Dictionary;
use WP_Term;

class Tag extends Taxonomy
{
    final protected const NAME_KEY = StandardTaxonomy::tag->name;

    protected static function getDictionary(): Dictionary
    {
        return Dictionary::getInstance();
    }
}

<?php

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;

class Tag extends Taxonomy
{
    final protected const NAME_KEY = StandardTaxonomy::tag->name;

    final protected function setWpTaxonomy(): void
    {
        $this->wpTaxonomy = get_taxonomy(self::NAME_KEY);
    }
}

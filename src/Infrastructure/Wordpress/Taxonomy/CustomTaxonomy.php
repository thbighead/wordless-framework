<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register;

abstract class CustomTaxonomy extends Taxonomy
{
    use Register;

    public const TAXONOMY_NAME_MAX_LENGTH = 32;
}

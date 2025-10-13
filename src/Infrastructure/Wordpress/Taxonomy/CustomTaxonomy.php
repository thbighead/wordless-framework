<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register;

abstract class CustomTaxonomy extends Taxonomy
{
    use Register;

    public const TAXONOMY_NAME_MAX_LENGTH = 32;

    public function hasParent(): bool
    {
        return static::isHierarchical() && parent::hasParent();
    }

    public function parent(): ?static
    {
        if (!static::isHierarchical()) {
            return null;
        }

        return parent::parent();
    }
}

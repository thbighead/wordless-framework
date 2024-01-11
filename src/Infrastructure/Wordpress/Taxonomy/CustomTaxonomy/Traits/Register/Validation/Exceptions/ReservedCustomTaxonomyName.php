<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions;

class ReservedCustomTaxonomyName extends InvalidCustomTaxonomyName
{
    protected function justification(): string
    {
        return 'This term is reserved by Wordpress core.';
    }
}

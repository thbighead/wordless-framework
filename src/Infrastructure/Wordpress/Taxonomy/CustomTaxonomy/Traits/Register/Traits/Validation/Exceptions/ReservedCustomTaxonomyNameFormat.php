<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions;

class ReservedCustomTaxonomyNameFormat extends InvalidCustomTaxonomyNameFormat
{
    protected function justification(): string
    {
        return 'This term is reserved by Wordpress core.';
    }
}

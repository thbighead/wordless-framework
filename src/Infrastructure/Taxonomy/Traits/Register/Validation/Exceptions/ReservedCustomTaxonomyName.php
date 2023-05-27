<?php

namespace Wordless\Infrastructure\Taxonomy\Traits\Register\Validation\Exceptions;

class ReservedCustomTaxonomyName extends InvalidCustomTaxonomyName
{
    protected function justification(): string
    {
        return 'This term is reserved by Wordpress core.';
    }
}

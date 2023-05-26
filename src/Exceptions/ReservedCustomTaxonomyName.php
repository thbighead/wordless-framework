<?php

namespace Wordless\Exceptions;

use Wordless\Application\Helpers\Reserved;
use Wordless\Infrastructure\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;

class ReservedCustomTaxonomyName extends InvalidCustomTaxonomyName
{
    protected function justification(): string
    {
        return 'The following taxonomy names are reserved and are already used by WordPress or should not be used as they interfere with other WordPress functions: '
            . json_encode(Reserved::getReservedTaxonomyNames());
    }
}

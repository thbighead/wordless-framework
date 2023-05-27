<?php

namespace Wordless\Infrastructure\CustomPost\Traits\Register\Validation\Exceptions;

class ReservedCustomPostTypeKey extends InvalidCustomPostTypeKey
{
    protected function justification(): string
    {
        return 'This term is reserved by Wordpress core.';
    }
}

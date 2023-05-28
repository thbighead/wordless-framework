<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions;

class ReservedCustomPostTypeKey extends InvalidCustomPostTypeKey
{
    protected function justification(): string
    {
        return 'This term is reserved by Wordpress core.';
    }
}

<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Registrar\CustomPostRegistrar\Traits\Validation\Exceptions;

class ReservedCustomPostTypeKeyFormat extends InvalidCustomPostTypeKeyFormat
{
    protected function justification(): string
    {
        return 'This term is reserved by Wordpress core.';
    }
}

<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\PostType;

class InvalidCustomPostTypeKey extends InvalidArgumentException
{
    public function __construct(public readonly string $invalid_type_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "The key '$this->invalid_type_key' is invalid for CPT key. {$this->justification()}",
            ExceptionCode::development_error->value,
            $previous
        );
    }

    protected function justification(): string
    {
        return 'A valid key must not exceed '
            . PostType::KEY_MAX_LENGTH
            . ' characters and may only contain lowercase alphanumeric characters, dashes, and underscores.';
    }
}

<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class ReservedCustomPostStatusKey extends InvalidArgumentException
{
    public function __construct(public readonly string $invalid_name_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "The key '$this->invalid_name_key' is invalid for post status name key. This term is reserved by Wordpress core.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

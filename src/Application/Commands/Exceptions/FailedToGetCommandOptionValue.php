<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGetCommandOptionValue extends RuntimeException
{
    public function __construct(readonly public string $option_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to retrieve '$this->option_key' command value.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

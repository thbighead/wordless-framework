<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Environment\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CannotResolveEnvironmentGet extends RuntimeException
{
    public function __construct(readonly public string $key, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed before resolving '$this->key' environment variable.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

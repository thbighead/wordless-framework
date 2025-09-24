<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Environment\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToLoadDotEnv extends RuntimeException
{
    public function __construct(readonly string $env_file_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Could not load .env in '$this->env_file_path'. {$previous->getMessage()}",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

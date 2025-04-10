<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PathTypeNotSupported extends DomainException
{
    public function __construct(readonly public string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Path $path is not a directory, file nor symbolic link.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

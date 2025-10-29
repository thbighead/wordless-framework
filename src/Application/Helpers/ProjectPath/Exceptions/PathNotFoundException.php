<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Exceptions\Traits\SettablePrevious;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PathNotFoundException extends ErrorException
{
    use SettablePrevious;

    public function __construct(public readonly string $path, ?Throwable $previous = null)
    {
        parent::__construct(
            "'$this->path' not found.",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }
}

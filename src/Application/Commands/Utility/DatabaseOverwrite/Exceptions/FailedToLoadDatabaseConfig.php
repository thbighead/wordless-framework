<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\DatabaseOverwrite\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToLoadDatabaseConfig extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('$message', ExceptionCode::development_error->value, $previous);
    }
}

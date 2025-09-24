<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\LogManager\Logger\RotatingFileHandler\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Application\Libraries\LogManager\Logger\RotatingFileHandler;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToResolveFilepath extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not resolve ' . RotatingFileHandler::class . ' filepath.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

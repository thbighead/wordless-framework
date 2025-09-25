<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\LogManager\Logger\RotatingFileHandler\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Application\Libraries\LogManager\Logger\RotatingFileHandler;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToConstructRotatingFileHandler extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not instantiate ' . RotatingFileHandler::class . ' object.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

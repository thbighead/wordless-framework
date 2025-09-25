<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\LogManager\Logger\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Application\Libraries\LogManager\Logger;
use Wordless\Infrastructure\Enums\ExceptionCode;

class LoggerInstantiationException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to construct ' . Logger::class . ' object.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

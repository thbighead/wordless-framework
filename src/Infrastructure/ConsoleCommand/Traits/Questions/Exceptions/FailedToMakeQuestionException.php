<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\Questions\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToMakeQuestionException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Failed to make question.', ExceptionCode::development_error->value, $previous);
    }
}

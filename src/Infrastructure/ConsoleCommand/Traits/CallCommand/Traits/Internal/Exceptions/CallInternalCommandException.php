<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CallInternalCommandException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to call internal command.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\OutputSection\Exceptions;

use LogicException;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TryingToUseSectionWithInvalidOutputInstance extends LogicException
{
    public function __construct(public readonly string $invalidOutputClass, ?Throwable $previous = null)
    {
        parent::__construct(
            'To use any section helper method you must assure that your current output is a '
            . ConsoleOutputInterface::class
            . " instance. $this->invalidOutputClass given.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

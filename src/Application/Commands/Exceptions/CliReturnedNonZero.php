<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CliReturnedNonZero extends ErrorException
{
    public function __construct(
        public readonly string   $full_command,
        public readonly Response $commandResponse,
        ?Throwable               $previous = null
    )
    {
        parent::__construct(
            "Running $this->full_command returned non-zero ({$this->commandResponse->result_code}) "
            . ($this->commandResponse->printedOutput() ? '' : 'not ')
            . 'printing output.',
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}

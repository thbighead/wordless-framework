<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Console\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToAddConsoleCommand extends RuntimeException
{
    public function __construct(readonly public ConsoleCommand $command, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to add ' . $this->command::COMMAND_NAME . ' command to application.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration\Contracts\IDecoration;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\VerboseOption;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\WrappedScript;

trait OutputMessage
{
    use DecoratedMessage;
    use TabledMessage;
    use VerboseOption;
    use WrappedScript;

    protected function write(string $message, ?IDecoration $decoration = null): void
    {
        $this->output->write($this->decorateText($message, $decoration));
    }

    protected function writeWhenVerbose(string $message, ?IDecoration $decoration = null): void
    {
        if ($this->isOutputVerbose()) {
            $this->write($message, $decoration);
        }
    }

    protected function writeln(string $message, ?IDecoration $decoration = null): void
    {
        $this->output->writeln($this->decorateText($message, $decoration));
    }

    protected function writelnWhenVerbose(string $message, ?IDecoration $decoration = null): void
    {
        if ($this->isOutputVerbose()) {
            $this->writeln($message, $decoration);
        }
    }
}

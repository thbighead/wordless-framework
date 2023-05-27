<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;

trait Warning
{
    protected function writelnWarning(string $message): void
    {
        $this->writeln($message, Decoration::warning);
    }

    protected function writelnWarningWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, Decoration::warning);
    }

    protected function writeWarning(string $message): void
    {
        $this->write($message, Decoration::warning);
    }

    protected function writeWarningWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, Decoration::warning);
    }
}

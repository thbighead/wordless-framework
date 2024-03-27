<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;

trait Success
{
    protected function writelnSuccess(string $message): void
    {
        $this->writeln($message, Decoration::success);
    }

    protected function writelnSuccessWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, Decoration::success);
    }

    protected function writeSuccess(string $message): void
    {
        $this->write($message, Decoration::success);
    }

    protected function writeSuccessWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, Decoration::success);
    }
}

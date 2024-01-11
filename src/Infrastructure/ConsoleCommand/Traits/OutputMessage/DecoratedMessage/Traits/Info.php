<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;

trait Info
{
    protected function writeInfo(string $message): void
    {
        $this->write($message, Decoration::info);
    }

    protected function writeInfoWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, Decoration::info);
    }

    protected function writelnInfo(string $message): void
    {
        $this->writeln($message, Decoration::info);
    }

    protected function writelnInfoWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, Decoration::info);
    }
}

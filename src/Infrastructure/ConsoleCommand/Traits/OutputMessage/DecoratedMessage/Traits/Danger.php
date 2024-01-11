<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;

trait Danger
{
    protected function writeDanger(string $message): void
    {
        $this->write($message, Decoration::danger);
    }

    protected function writeDangerWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, Decoration::danger);
    }

    protected function writelnDanger(string $message): void
    {
        $this->writeln($message, Decoration::danger);
    }

    protected function writelnDangerWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, Decoration::danger);
    }
}

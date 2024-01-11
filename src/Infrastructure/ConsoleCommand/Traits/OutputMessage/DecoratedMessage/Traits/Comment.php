<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;

trait Comment
{
    protected function writeComment(string $message): void
    {
        $this->write($message, Decoration::comment);
    }

    protected function writeCommentWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, Decoration::comment);
    }

    protected function writelnComment(string $message): void
    {
        $this->writeln($message, Decoration::comment);
    }

    protected function writelnCommentWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, Decoration::comment);
    }
}

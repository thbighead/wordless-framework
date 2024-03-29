<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

trait VerboseOption
{
    protected function isOutputVerbose(): bool
    {
        return $this->isV() || $this->isVV() || $this->isVVV();
    }

    protected function isV(): bool
    {
        return $this->output->isVerbose();
    }

    protected function isVV(): bool
    {
        return $this->output->isVeryVerbose();
    }

    protected function isVVV(): bool
    {
        return $this->output->isDebug();
    }
}

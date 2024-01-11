<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Wordless\Application\Libraries\TypeBackedEnum\StringBackedEnum;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration\Contracts\IDecoration;

trait Setup
{
    protected function getOutputFormatter(): OutputFormatterInterface
    {
        return $this->output->getFormatter();
    }

    protected function setupOutputStyle(StringBackedEnum&IDecoration $decoration): static
    {
        $this->getOutputFormatter()
            ->setStyle($decoration->name(), new OutputFormatterStyle($decoration->color()));

        return $this;
    }

    protected function setupOutputStyles(): static
    {
        $this->setupOutputStyle(Decoration::comment)
            ->setupOutputStyle(Decoration::danger)
            ->setupOutputStyle(Decoration::info)
            ->setupOutputStyle(Decoration::success)
            ->setupOutputStyle(Decoration::warning);

        return $this;
    }

    protected function turnOnDecoratedOutputs(): void
    {
        $this->output->setDecorated(true);
    }
}

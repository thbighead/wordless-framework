<?php

namespace Wordless\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\ConsoleCommandInstantiator;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

abstract class ConsoleCommand extends Command
{
    use CallCommand;
    use ConsoleCommandInstantiator;
    use OutputMessage;

    final public const DONE_MESSAGE = ' Done!';

    protected InputInterface $input;
    protected OutputInterface $output;

    /**
     * @return ArgumentDTO[]
     */
    abstract protected function arguments(): array;

    abstract protected function description(): string;

    abstract protected function help(): string;

    /**
     * @return OptionDTO[]
     */
    abstract protected function options(): array;

    abstract protected function runIt(): int;

    protected function configure(): void
    {
        $this->setArguments(...$this->arguments())
            ->setOptions(...$this->options())
            ->setDescription($this->description())
            ->setHelp($this->help());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setup($input, $output);

        return $this->runIt();
    }

    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $this->setupOutputStyles()
            ->turnOnDecoratedOutputs();
    }

    private function mountBufferedOutput(): BufferedOutput
    {
        return new BufferedOutput($this->output->getVerbosity(), true);
    }

    private function setArguments(ArgumentDTO ...$arguments): static
    {
        foreach ($arguments as $argument) {
            $this->addArgument(
                $argument->name,
                $argument->mode?->value,
                $argument->description,
                $argument->default
            );
        }

        return $this;
    }

    private function setOptions(OptionDTO ...$options): static
    {
        foreach ($options as $option) {
            $this->addOption(
                $option->name,
                $option->shortcut,
                $option->mode?->value,
                $option->description,
                $option->default
            );
        }

        return $this;
    }
}

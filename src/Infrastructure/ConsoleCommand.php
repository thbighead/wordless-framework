<?php

namespace Wordless\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\ConsoleCommandInstantiator;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;
use Wordless\Infrastructure\ConsoleCommand\Traits\Questions;

abstract class ConsoleCommand extends Command
{
    use CallCommand;
    use ConsoleCommandInstantiator;
    use OutputMessage;
    use Questions;

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

    public function canRun(): bool
    {
        return true;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
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

    /**
     * @param ArgumentDTO ...$arguments
     * @return $this
     * @throws InvalidArgumentException
     */
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

    /**
     * @param OptionDTO ...$options
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setOptions(OptionDTO ...$options): static
    {
        foreach ($options as $option) {
            $this->addOption(
                $option->name,
                $option->shortcut,
                $option->mode?->value,
                $option->description,
                OptionMode::optional_value && $option->default === null ? false : $option->default
            );
        }

        return $this;
    }
}

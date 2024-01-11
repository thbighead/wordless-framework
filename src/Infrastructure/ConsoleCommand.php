<?php declare(strict_types=1);

namespace Wordless\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\ConsoleCommandInstantiator;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;
use Wordless\Infrastructure\ConsoleCommand\Traits\Questions;
use Wordless\Infrastructure\ConsoleCommand\Traits\SignalResolver;

abstract class ConsoleCommand extends Command implements SignalableCommandInterface
{
    use CallCommand;
    use ConsoleCommandInstantiator;
    use OutputMessage;
    use Questions;
    use SignalResolver;

    public const COMMAND_NAME = '';
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

    public function __toString(): string
    {
        return "php console {$this->input->__toString()}";
    }

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

    /**
     * @param ArgumentDTO $argument
     * @param ArgumentDTO ...$arguments
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setArguments(ArgumentDTO $argument, ArgumentDTO ...$arguments): static
    {
        array_unshift($arguments, $argument);

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
     * @param OptionDTO $option
     * @param OptionDTO ...$options
     * @return $this
     * @throws InvalidArgumentException
     */
    private function setOptions(OptionDTO $option, OptionDTO ...$options): static
    {
        array_unshift($options, $option);

        foreach ($options as $option) {
            $optionMode = $option->mode;

            $this->addOption(
                $option->name,
                $option->shortcut,
                $optionMode?->value,
                $option->description,
                $optionMode === OptionMode::optional_value && $option->default === null ?
                    false : $option->default
            );
        }

        return $this;
    }
}

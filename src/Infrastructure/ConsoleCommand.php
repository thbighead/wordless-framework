<?php

namespace Wordless\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class ConsoleCommand extends Command
{
    final public const DECORATION_COMMENT = 'comment';
    final public const DECORATION_DANGER = 'danger';
    final public const DECORATION_ERROR = 'error';
    final public const DECORATION_INFO = 'info';
    final public const DECORATION_QUESTION = 'question';
    final public const DECORATION_SUCCESS = 'success';
    final public const DECORATION_WARNING = 'warning';

    final protected const ARGUMENT_DEFAULT_FIELD = 'default';
    final protected const ARGUMENT_DESCRIPTION_FIELD = 'description';
    final protected const ARGUMENT_MODE_FIELD = 'mode';
    final protected const ARGUMENT_NAME_FIELD = 'name';
    final protected const OPTION_DEFAULT_FIELD = 'default';
    final protected const OPTION_DESCRIPTION_FIELD = 'description';
    final protected const OPTION_MODE_FIELD = 'mode';
    final protected const OPTION_NAME_FIELD = 'name';
    final protected const OPTION_SHORTCUT_FIELD = 'shortcut';

    protected InputInterface $input;
    protected OutputInterface $output;
    private array $wordlessCommandsCache = [];

    abstract protected function arguments(): array;

    abstract protected function description(): string;

    abstract protected function help(): string;

    abstract protected function options(): array;

    abstract protected function runIt(): int;

    protected function configure(): void
    {
        $this->setDescription($this->description())
            ->setHelp($this->help());

        foreach ($this->arguments() as $argument) {
            $this->addArgument(
                $argument[self::ARGUMENT_NAME_FIELD],
                $argument[self::ARGUMENT_MODE_FIELD] ?? null,
                $argument[self::ARGUMENT_DESCRIPTION_FIELD] ?? '',
                $argument[self::ARGUMENT_DEFAULT_FIELD] ?? null
            );
        }

        foreach ($this->options() as $option) {
            $this->addOption(
                $option[self::OPTION_NAME_FIELD],
                $option[self::OPTION_SHORTCUT_FIELD] ?? null,
                $option[self::OPTION_MODE_FIELD] ?? null,
                $option[self::OPTION_DESCRIPTION_FIELD] ?? '',
                $option[self::OPTION_DEFAULT_FIELD] ?? null
            );
        }
    }

    protected function decorateText(string $text, ?string $decoration = null): string
    {
        if ($decoration !== null) {
            return "<$decoration>$text</>";
        }

        return $text;
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

    protected function executeCommand(string $full_command): int
    {
        if ($this->output instanceof BufferedOutput) {
            exec($full_command, $output, $result_code);
            $this->output->writeln($output);

            return $result_code;
        }

        return Process::fromShellCommandline($full_command)
            ->setTty(true)
            ->run(function ($type, $buffer) {
                echo $buffer;
            });
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @param OutputInterface|null $output
     * @return int|string
     * @throws ExceptionInterface
     */
    protected function executeWordlessCommand(
        string           $command_name,
        array            $inputs = [],
        ?OutputInterface $output = null
    ): int|string
    {
        if (!($output instanceof OutputInterface)) {
            $output = $this->mountBufferedOutput();
        }

        $return_code = $this->getOrSaveAndGetFromWordlessCommandsCache($command_name)
            ->run(new ArrayInput($inputs), $output);

        return $output instanceof BufferedOutput ? $output->fetch() : $return_code;
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @param BufferedOutput|null $output
     * @return string
     * @throws ExceptionInterface
     */
    protected function executeWordlessCommandGettingOutput(
        string          $command_name,
        array           $inputs = [],
        ?BufferedOutput $output = null
    ): string
    {
        if (!($output instanceof BufferedOutput)) {
            $output = $this->mountBufferedOutput();
        }

        $this->getOrSaveAndGetFromWordlessCommandsCache($command_name)->run(new ArrayInput($inputs), $output);

        return $output->fetch();
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

    /**
     * https://symfony.com/doc/current/components/console/helpers/table.html
     *
     * @return Table
     */
    protected function mountTable(): Table
    {
        return new Table($this->output);
    }

    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $this->setupOutputStyles()
            ->turnOnDecoratedOutputs();
    }

    protected function setupOutputStyle(string $name, string $color): static
    {
        $this->output->getFormatter()->setStyle($name, new OutputFormatterStyle($color));

        return $this;
    }

    protected function setupOutputStyles(): static
    {
        $this->setupOutputStyle(self::DECORATION_COMMENT, 'gray')
            ->setupOutputStyle(self::DECORATION_DANGER, 'red')
            ->setupOutputStyle(self::DECORATION_INFO, 'cyan')
            ->setupOutputStyle(self::DECORATION_SUCCESS, 'bright-green')
            ->setupOutputStyle(self::DECORATION_WARNING, 'yellow');

        return $this;
    }

    protected function turnOnDecoratedOutputs(): void
    {
        $this->output->setDecorated(true);
    }

    protected function wrapScriptWithMessages(
        string   $before_script_message,
        callable $script,
        string   $after_script_message = ' Done!',
        bool     $only_when_verbose = false
    )
    {
        $only_when_verbose ?
            $this->writeWhenVerbose($before_script_message) : $this->write($before_script_message);

        $result = $script();

        $only_when_verbose ?
            $this->writelnSuccessWhenVerbose($after_script_message) : $this->writelnSuccess($after_script_message);

        return $result;
    }

    protected function wrapScriptWithMessagesWhenVerbose(
        string   $before_script_message,
        callable $script,
        string   $after_script_message = ' Done!'
    ): void
    {
        $this->wrapScriptWithMessages(
            $before_script_message,
            $script,
            $after_script_message,
            true
        );
    }

    protected function write(string $message, ?string $decoration = null): void
    {
        $this->output->write($this->decorateText($message, $decoration));
    }

    protected function writeComment(string $message): void
    {
        $this->write($message, self::DECORATION_COMMENT);
    }

    protected function writeCommentWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_COMMENT);
    }

    protected function writeDanger(string $message): void
    {
        $this->write($message, self::DECORATION_DANGER);
    }

    protected function writeDangerWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_DANGER);
    }

    protected function writeError(string $message): void
    {
        $this->write($message, self::DECORATION_ERROR);
    }

    protected function writeErrorWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_ERROR);
    }

    protected function writeInfo(string $message): void
    {
        $this->write($message, self::DECORATION_INFO);
    }

    protected function writeInfoWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_INFO);
    }

    protected function writeln(string $message, ?string $decoration = null): void
    {
        $this->output->writeln($this->decorateText($message, $decoration));
    }

    protected function writelnComment(string $message): void
    {
        $this->writeln($message, self::DECORATION_COMMENT);
    }

    protected function writelnCommentWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_COMMENT);
    }

    protected function writelnDanger(string $message): void
    {
        $this->writeln($message, self::DECORATION_DANGER);
    }

    protected function writelnDangerWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_DANGER);
    }

    protected function writelnError(string $message): void
    {
        $this->writeln($message, self::DECORATION_ERROR);
    }

    protected function writelnErrorWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_ERROR);
    }

    protected function writelnInfo(string $message): void
    {
        $this->writeln($message, self::DECORATION_INFO);
    }

    protected function writelnInfoWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_INFO);
    }

    protected function writelnQuestion(string $message): void
    {
        $this->writeln($message, self::DECORATION_QUESTION);
    }

    protected function writelnQuestionWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_QUESTION);
    }

    protected function writelnSuccess(string $message): void
    {
        $this->writeln($message, self::DECORATION_SUCCESS);
    }

    protected function writelnSuccessWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_SUCCESS);
    }

    protected function writelnWarning(string $message): void
    {
        $this->writeln($message, self::DECORATION_WARNING);
    }

    protected function writelnWarningWhenVerbose(string $message): void
    {
        $this->writelnWhenVerbose($message, self::DECORATION_WARNING);
    }

    protected function writelnWhenVerbose(string $message, ?string $decoration = null): void
    {
        if ($this->isV()) {
            $this->writeln($message, $decoration);
        }
    }

    protected function writeQuestion(string $message): void
    {
        $this->write($message, self::DECORATION_QUESTION);
    }

    protected function writeQuestionWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_QUESTION);
    }

    protected function writeSuccess(string $message): void
    {
        $this->write($message, self::DECORATION_SUCCESS);
    }

    protected function writeSuccessWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_SUCCESS);
    }

    protected function writeWarning(string $message): void
    {
        $this->write($message, self::DECORATION_WARNING);
    }

    protected function writeWarningWhenVerbose(string $message): void
    {
        $this->writeWhenVerbose($message, self::DECORATION_WARNING);
    }

    protected function writeWhenVerbose(string $message, ?string $decoration = null): void
    {
        if ($this->isV()) {
            $this->write($message, $decoration);
        }
    }

    private function getOrSaveAndGetFromWordlessCommandsCache(string $command_name): Command
    {
        $commandObject = $this->wordlessCommandsCache[$command_name] ?? null;

        if (!($commandObject instanceof Command)) {
            $commandObject = $this->wordlessCommandsCache[$command_name] =
                $this->getApplication()->find($command_name);
        }

        return $commandObject;
    }

    private function mountBufferedOutput(): BufferedOutput
    {
        return new BufferedOutput($this->output->getVerbosity(), true);
    }
}

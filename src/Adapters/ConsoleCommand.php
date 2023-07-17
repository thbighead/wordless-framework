<?php

namespace Wordless\Adapters;

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
    public const DECORATION_COMMENT = 'comment';
    public const DECORATION_DANGER = 'danger';
    public const DECORATION_ERROR = 'error';
    public const DECORATION_INFO = 'info';
    public const DECORATION_QUESTION = 'question';
    public const DECORATION_SUCCESS = 'success';
    public const DECORATION_WARNING = 'warning';

    protected const ARGUMENT_DEFAULT_FIELD = 'default';
    protected const ARGUMENT_DESCRIPTION_FIELD = 'description';
    protected const ARGUMENT_MODE_FIELD = 'mode';
    protected const ARGUMENT_NAME_FIELD = 'name';
    protected const OPTION_DEFAULT_FIELD = 'default';
    protected const OPTION_DESCRIPTION_FIELD = 'description';
    protected const OPTION_MODE_FIELD = 'mode';
    protected const OPTION_NAME_FIELD = 'name';
    protected const OPTION_SHORTCUT_FIELD = 'shortcut';

    protected InputInterface $input;
    protected OutputInterface $output;
    private array $wordlessCommandsCache = [];

    abstract protected function arguments(): array;

    abstract protected function description(): string;

    abstract protected function help(): string;

    abstract protected function options(): array;

    abstract protected function runIt(): int;

    public function canRun(): bool
    {
        return true;
    }

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
        } else {
            $result_code = Process::fromShellCommandline($full_command)
                ->setTty(true)
                ->setTimeout(null)
                ->run(function ($type, $buffer) {
                    echo $buffer;
                });
        }

        return $result_code;
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
    )
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

    protected function setOutputStyles()
    {
        $this->output->getFormatter()->setStyle(
            self::DECORATION_COMMENT,
            new OutputFormatterStyle('gray')
        );
        $this->output->getFormatter()->setStyle(
            self::DECORATION_DANGER,
            new OutputFormatterStyle('red')
        );
        $this->output->getFormatter()->setStyle(
            self::DECORATION_INFO,
            new OutputFormatterStyle('cyan')
        );
        $this->output->getFormatter()->setStyle(
            self::DECORATION_SUCCESS,
            new OutputFormatterStyle('bright-green')
        );
        $this->output->getFormatter()->setStyle(
            self::DECORATION_WARNING,
            new OutputFormatterStyle('yellow')
        );
    }

    protected function setup(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->setOutputStyles();

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
    )
    {
        $this->wrapScriptWithMessages(
            $before_script_message,
            $script,
            $after_script_message,
            true
        );
    }

    protected function write(string $message, ?string $decoration = null)
    {
        $this->output->write($this->decorateText($message, $decoration));
    }

    protected function writeComment(string $message)
    {
        $this->write($message, self::DECORATION_COMMENT);
    }

    protected function writeCommentWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_COMMENT);
    }

    protected function writeDanger(string $message)
    {
        $this->write($message, self::DECORATION_DANGER);
    }

    protected function writeDangerWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_DANGER);
    }

    protected function writeError(string $message)
    {
        $this->write($message, self::DECORATION_ERROR);
    }

    protected function writeErrorWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_ERROR);
    }

    protected function writeInfo(string $message)
    {
        $this->write($message, self::DECORATION_INFO);
    }

    protected function writeInfoWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_INFO);
    }

    protected function writeln(string $message, ?string $decoration = null)
    {
        $this->output->writeln($this->decorateText($message, $decoration));
    }

    protected function writelnComment(string $message)
    {
        $this->writeln($message, self::DECORATION_COMMENT);
    }

    protected function writelnCommentWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_COMMENT);
    }

    protected function writelnDanger(string $message)
    {
        $this->writeln($message, self::DECORATION_DANGER);
    }

    protected function writelnDangerWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_DANGER);
    }

    protected function writelnError(string $message)
    {
        $this->writeln($message, self::DECORATION_ERROR);
    }

    protected function writelnErrorWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_ERROR);
    }

    protected function writelnInfo(string $message)
    {
        $this->writeln($message, self::DECORATION_INFO);
    }

    protected function writelnInfoWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_INFO);
    }

    protected function writelnQuestion(string $message)
    {
        $this->writeln($message, self::DECORATION_QUESTION);
    }

    protected function writelnQuestionWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_QUESTION);
    }

    protected function writelnSuccess(string $message)
    {
        $this->writeln($message, self::DECORATION_SUCCESS);
    }

    protected function writelnSuccessWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_SUCCESS);
    }

    protected function writelnWarning(string $message)
    {
        $this->writeln($message, self::DECORATION_WARNING);
    }

    protected function writelnWarningWhenVerbose(string $message)
    {
        $this->writelnWhenVerbose($message, self::DECORATION_WARNING);
    }

    protected function writelnWhenVerbose(string $message, ?string $decoration = null)
    {
        if ($this->isV()) {
            $this->writeln($message, $decoration);
        }
    }

    protected function writeQuestion(string $message)
    {
        $this->write($message, self::DECORATION_QUESTION);
    }

    protected function writeQuestionWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_QUESTION);
    }

    protected function writeSuccess(string $message)
    {
        $this->write($message, self::DECORATION_SUCCESS);
    }

    protected function writeSuccessWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_SUCCESS);
    }

    protected function writeWarning(string $message)
    {
        $this->write($message, self::DECORATION_WARNING);
    }

    protected function writeWarningWhenVerbose(string $message)
    {
        $this->writeWhenVerbose($message, self::DECORATION_WARNING);
    }

    protected function writeWhenVerbose(string $message, ?string $decoration = null)
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

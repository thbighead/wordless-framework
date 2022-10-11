<?php

namespace Wordless\Adapters;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class WordlessCommand extends Command
{
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
            passthru($full_command, $result_code);
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
            $output = new BufferedOutput;
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
            $output = new BufferedOutput;
        }

        $this->getOrSaveAndGetFromWordlessCommandsCache($command_name)->run(new ArrayInput($inputs), $output);

        return $output->fetch();
    }

    protected function setup(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    protected function wrapScriptWithMessages(
        string   $before_script_message,
        callable $script,
        string   $after_script_message = ' Done!',
        bool     $only_when_verbose = false
    )
    {
        $only_when_verbose ?
            $this->writeWhenVerbose($before_script_message) : $this->output->write($before_script_message);

        $result = $script();

        $only_when_verbose ?
            $this->writelnWhenVerbose($after_script_message) : $this->output->writeln($after_script_message);

        return $result;
    }

    protected function wrapScriptWithMessagesWhenVerbose(string $before_script_message, callable $script)
    {
        $this->wrapScriptWithMessages($before_script_message, $script, ' Done!', true);
    }

    protected function writelnWhenVerbose(string $message)
    {
        if ($this->output->isVerbose()) {
            $this->output->writeln($message);
        }
    }

    protected function writeWhenVerbose(string $message)
    {
        if ($this->output->isVerbose()) {
            $this->output->write($message);
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
}

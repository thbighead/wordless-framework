<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class WpCliCaller extends WordlessCommand
{
    public const COMMAND_NAME = 'wp:run';
    public const WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME = 'wp_cli_full_command_string';
    private const NON_WINDOWS_OS = 'non-windows';
    private const OUTPUT_AS_STRING_MODE = 'output-as-string';
    private const PARTIAL_REWRITE_STRUCTURE_COMMAND = 'rewrite structure ';
    private const WINDOWS_OS = 'windows';

    private InputInterface $input;
    private array $modes;
    private OutputInterface $output;

    protected static $defaultName = self::COMMAND_NAME;

    private string $operational_system;

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DEFAULT_FIELD => '',
                self::ARGUMENT_DESCRIPTION_FIELD => 'A string containing exactly what you want to run with "wp"',
                self::ARGUMENT_MODE_FIELD => InputArgument::OPTIONAL,
                self::ARGUMENT_NAME_FIELD => self::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Runs a WP-CLI command automatically choosing script according to OS.';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws PathNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setup($input, $output);

        $wp_cli_full_command_string = $this->input->getArgument(self::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME);
        $this->treatWpCliCommand($wp_cli_full_command_string);
        $wp_cli_filepath = $this->chooseWpCliScriptByOperationalSystem();
        $full_command = "$wp_cli_filepath $wp_cli_full_command_string";

        if ($output->isVerbose()) {
            $output->writeln("Executing $full_command...");
        }

        return $this->executeCommand($full_command);
    }

    protected function help(): string
    {
        return 'Instead of choosing the right script according to your OS and executing it with absolute path inside vendor/bin folder, just run php console wp:run "{command}".';
    }

    protected function options(): array
    {
        return [
            [
                self::OPTION_NAME_FIELD => self::OUTPUT_AS_STRING_MODE,
                self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
                self::OPTION_DESCRIPTION_FIELD => 'Returns the entire command output as string to OutputInterface.',
            ],
        ];
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    private function chooseWpCliScriptByOperationalSystem(): string
    {
        $script_filename = $this->isOnWindows() ? 'wp.bat' : 'wp';

        return ProjectPath::vendor("bin/$script_filename");
    }

    private function executeCommand(string $full_command): int
    {
        if ($this->modes[self::OUTPUT_AS_STRING_MODE]) {
            exec($full_command, $output, $result_code);
            $this->output->writeln($output);
        } else {
            passthru($full_command, $result_code);
        }

        return $result_code;
    }

    private function guessOperationalSystem(): string
    {
        if (isset($this->operational_system)) {
            return $this->operational_system;
        }

        return $this->operational_system = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ?
            self::WINDOWS_OS : self::NON_WINDOWS_OS;
    }

    private function isOnWindows(): bool
    {
        return $this->guessOperationalSystem() === self::WINDOWS_OS;
    }

    private function setup(InputInterface $input, OutputInterface $output)
    {
        $this->modes = [
            self::OUTPUT_AS_STRING_MODE => $input->getOption(self::OUTPUT_AS_STRING_MODE),
        ];
        $this->input = $input;
        $this->output = $output;
    }

    private function treatRewriteStructureOnWindows(string &$wp_cli_full_command_string)
    {
        if ($this->isOnWindows()
            && Str::beginsWith($wp_cli_full_command_string, self::PARTIAL_REWRITE_STRUCTURE_COMMAND)) {
            /**
             * Trimming any '/' char from beginning of <permastruct> option of rewrite structure command
             * (https://developer.wordpress.org/cli/commands/rewrite/structure/#options) to avoid strange git full
             * pathing (https://github.com/wp-cli/wp-cli/issues/2677#issue-149924458)
             */
            $wp_cli_full_command_string = preg_replace(
                '/(\'|")?\/(.+)(\'|")?/',
                '$1$2$3',
                $wp_cli_full_command_string
            );
        }
    }

    private function treatWpCliCommand(string &$wp_cli_full_command_string)
    {
        $this->treatRewriteStructureOnWindows($wp_cli_full_command_string);
    }
}

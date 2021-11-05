<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
    private const PARTIAL_REWRITE_STRUCTURE_COMMAND = 'rewrite structure ';
    private const WINDOWS_OS = 'windows';

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
        $wp_cli_full_command_string = $input->getArgument(self::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME);
        $this->treatWpCliCommand($wp_cli_full_command_string);
        $wp_cli_filepath = $this->chooseWpCliScriptByOperationalSystem();
        $full_command = "$wp_cli_filepath $wp_cli_full_command_string --path=public_html/wp-cms/wp-core";

        if ($output->isVerbose()) {
            $output->writeln("Executing $full_command...");
        }

        passthru($full_command, $return_var);

        return $return_var;
    }

    protected function help(): string
    {
        return 'Instead of choosing the right script according to your OS and executing it with absolute path inside vendor/bin folder, just run php console wp:run "{command}".';
    }

    protected function options(): array
    {
        return [];
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

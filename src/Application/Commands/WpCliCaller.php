<?php

namespace Wordless\Application\Commands;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO\Enums\ArgumentMode;

class WpCliCaller extends ConsoleCommand
{
    final public const COMMAND_NAME = 'wp:run';
    final public const WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME = 'wp_cli_full_command_string';
    private const NON_WINDOWS_OS = 'non-windows';
    private const PARTIAL_REWRITE_STRUCTURE_COMMAND = 'rewrite structure ';
    private const WINDOWS_OS = 'windows';

    protected static $defaultName = self::COMMAND_NAME;

    private string $operational_system;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
                self::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME,
                'A string containing exactly what you want to run with "wp"',
                ArgumentMode::optional,
                ''
            ),
        ];
    }

    protected function description(): string
    {
        return 'Runs a WP-CLI command automatically choosing script according to OS.';
    }

    /**
     * @return int
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $wp_cli_full_command_string = $this->input->getArgument(self::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME);
        $this->treatWpCliCommand($wp_cli_full_command_string);
        $wp_cli_filepath = $this->chooseWpCliScriptByOperationalSystem();
        $full_command = "$wp_cli_filepath $wp_cli_full_command_string";

        $this->writelnInfoWhenVerbose("Executing $full_command...");

        return $this->callExternalCommand($full_command);
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

    private function treatRewriteStructureOnWindows(string &$wp_cli_full_command_string): void
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

    private function treatWpCliCommand(string &$wp_cli_full_command_string): void
    {
        $this->treatRewriteStructureOnWindows($wp_cli_full_command_string);
    }
}

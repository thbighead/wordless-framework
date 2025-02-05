<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Process\Exception\InvalidArgumentException as SymfonyProcessInvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Symfony\Component\Process\Exception\ProcessStartFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Traits\NoTtyMode;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class WpCliCaller extends ConsoleCommand
{
    use NoTtyMode;

    final public const COMMAND_NAME = 'wp:run';
    final public const WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME = 'wp_cli_full_command_string';
    private const NON_WINDOWS_OS = 'non-windows';
    private const PARTIAL_REWRITE_STRUCTURE_COMMAND = 'rewrite structure ';
    private const WINDOWS_OS = 'windows';

    private string $operational_system;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
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
     * @throws CliReturnedNonZero
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws PathNotFoundException
     * @throws ProcessSignaledException
     * @throws ProcessStartFailedException
     * @throws ProcessTimedOutException
     * @throws RuntimeException
     * @throws SymfonyProcessInvalidArgumentException
     */
    protected function runIt(): int
    {
        $wp_cli_filepath = $this->chooseWpCliScriptByOperationalSystem();
        $wp_cli_full_command_string = $this->input->getArgument(self::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME);

        $this->resolveWpCliCommand($wp_cli_full_command_string);

        $full_command = "$wp_cli_filepath $wp_cli_full_command_string";

        $this->writelnInfoWhenVerbose("Executing $full_command...");

        if ($this->output instanceof BufferedOutput) {
            $commandResponse = $this->isQuiet()
                ? $this->callExternalCommandSilentlyWithoutInterruption($full_command)
                : $this->callExternalCommandSilently($full_command);

            $this->write($commandResponse->output);

            return $commandResponse->result_code;
        }

        if ($this->isQuiet()) {
            return $this->callExternalCommandSilentlyWithoutInterruption($full_command)->result_code;
        }

        return $this->callExternalCommand($full_command, !$this->isNoTtyMode())->result_code;
    }

    protected function help(): string
    {
        return 'Instead of choosing the right script according to your OS and executing it with absolute path inside vendor/bin folder, just run php console wp:run "{command}".';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            $this->mountNoTtyOption(),
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

    private function guessOperationalSystem(): string
    {
        if (isset($this->operational_system)) {
            return $this->operational_system;
        }

        return $this->operational_system = Str::of(PHP_OS)->upper()->beginsWith('WIN') ?
            self::WINDOWS_OS : self::NON_WINDOWS_OS;
    }

    private function isOnWindows(): bool
    {
        return $this->guessOperationalSystem() === self::WINDOWS_OS;
    }

    private function resolveRewriteStructureOnWindows(string &$wp_cli_full_command_string): void
    {
        if ($this->isOnWindows()
            && Str::beginsWith($wp_cli_full_command_string, self::PARTIAL_REWRITE_STRUCTURE_COMMAND)) {
            /**
             * Trimming any '/' char from beginning of <permastruct> option of rewrite structure command
             * (https://developer.wordpress.org/cli/commands/rewrite/structure/#options) to avoid strange git full
             * pathing (https://github.com/wp-cli/wp-cli/issues/2677#issue-149924458)
             */
            $wp_cli_full_command_string = preg_replace(
                '/([\'"])?\/(.+)\1?/',
                '$1$2$1',
                $wp_cli_full_command_string
            );
        }
    }

    /**
     * @param string $wp_cli_full_command_string
     * @return void
     * @throws InvalidArgumentException
     */
    private function resolveWpCliCommand(string &$wp_cli_full_command_string): void
    {
        $this->resolveRewriteStructureOnWindows($wp_cli_full_command_string);

        if ($this->isQuiet()) {
            $wp_cli_full_command_string = "$wp_cli_full_command_string --quiet";
        }
    }
}

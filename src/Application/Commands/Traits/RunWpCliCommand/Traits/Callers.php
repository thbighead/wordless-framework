<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Traits\NoTtyMode;
use Wordless\Application\Commands\Traits\NoTtyMode\DTO\NoTtyModeOptionDTO;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToCallWpCliCommand;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;

trait Callers
{
    use NoTtyMode;

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToCallWpCliCommand
     * @throws WpCliCommandReturnedNonZero
     */
    private function callWpCliCommand(string $wp_cli_command): Response
    {
        try {
            return $this->callConsoleCommand(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $wp_cli_command,
                '--' . NoTtyModeOptionDTO::NO_TTY_MODE => $this->isNoTtyMode(),
            ]);
        } catch (CliReturnedNonZero $exception) {
            throw new WpCliCommandReturnedNonZero($exception->full_command, $exception->commandResponse, $exception);
        } catch (InvalidArgumentException|CallInternalCommandException $exception) {
            throw new FailedToCallWpCliCommand($exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToCallWpCliCommand
     * @throws WpCliCommandReturnedNonZero
     */
    private function callWpCliCommandSilently(string $wp_cli_command): Response
    {
        try {
            return $this->callConsoleCommandSilently(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $wp_cli_command,
                '--' . NoTtyModeOptionDTO::NO_TTY_MODE => true,
            ]);
        } catch (CliReturnedNonZero $exception) {
            throw new WpCliCommandReturnedNonZero($exception->full_command, $exception->commandResponse, $exception);
        } catch (CallInternalCommandException $exception) {
            throw new FailedToCallWpCliCommand($exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToCallWpCliCommand
     */
    private function callWpCliCommandSilentlyWithoutInterruption(string $wp_cli_command): Response
    {
        try {
            return $this->callWpCliCommandSilently($wp_cli_command);
        } catch (WpCliCommandReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToCallWpCliCommand
     */
    private function callWpCliCommandWithoutInterruption(string $wp_cli_command): Response
    {
        try {
            return $this->callWpCliCommand($wp_cli_command);
        } catch (WpCliCommandReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }
}

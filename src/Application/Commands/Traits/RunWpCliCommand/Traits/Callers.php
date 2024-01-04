<?php

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;

trait Callers
{
    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function callWpCliCommand(string $wp_cli_command): Response
    {
        try {
            return $this->callConsoleCommand(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $wp_cli_command,
            ]);
        } catch (CliReturnedNonZero $exception) {
            throw new WpCliCommandReturnedNonZero($exception->full_command, $exception->commandResponse, $exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function callWpCliCommandSilently(string $wp_cli_command): Response
    {
        try {
            return $this->callConsoleCommandSilently(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $wp_cli_command,
            ]);
        } catch (CliReturnedNonZero $exception) {
            throw new WpCliCommandReturnedNonZero($exception->full_command, $exception->commandResponse, $exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
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
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
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

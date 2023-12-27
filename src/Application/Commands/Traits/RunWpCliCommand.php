<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Traits\AllowRootMode\DTO\AllowRootModeOptionDTO;
use Wordless\Application\Commands\Traits\WunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\WpCliCaller;

trait RunWpCliCommand
{
    use AllowRootMode;

    /**
     * @param string $command
     * @param bool $return_script_code
     * @return int|string
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws CliReturnedNonZero
     */
    private function callWpCliCommand(string $command, bool $return_script_code = true): int|string
    {
        return $this->callConsoleCommand(WpCliCaller::COMMAND_NAME, [
            WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
        ], $return_script_code);
    }

    /**
     * @param string $command
     * @return $this
     * @throws InvalidArgumentException
     */
    private function resolveCommandAllowRootMode(string &$command): static
    {
        if ($this->isAllowRootMode()) {
            $command = "$command --" . AllowRootModeOptionDTO::ALLOW_ROOT_MODE;
        }

        return $this;
    }

    private function resolveCommandDebugMode(string &$command): static
    {
        if ($this->isVVV()) {
            $command = "$command --debug";
        }

        return $this;
    }

    /**
     * @param string $command
     * @param bool $return_script_code
     * @return int|string
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function runWpCliCommand(string $command, bool $return_script_code = false): int|string
    {
        try {
            return $this->resolveCommandAllowRootMode($command)
                ->resolveCommandDebugMode($command)
                ->callWpCliCommand($command, $return_script_code);
        } catch (CliReturnedNonZero $exception) {
            throw new WpCliCommandReturnedNonZero(
                $exception->full_command,
                $exception->script_result_code,
                $exception->script_result_output,
                $exception
            );
        }
    }

    /**
     * @param string $command
     * @return string
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    private function runWpCliCommandReturningOutputWithoutInterruption(string $command): string
    {
        try {
            return $this->runWpCliCommand($command);
        } catch (WpCliCommandReturnedNonZero $exception) {
            $this->writelnWarningWhenVerbose($exception->getMessage());

            return $exception->script_result_output;
        }
    }
}

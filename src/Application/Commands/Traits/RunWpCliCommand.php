<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
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
     * @throws ExceptionInterface
     * @throws CommandNotFoundException
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
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function runWpCliCommand(string $command, bool $return_script_code = true): int|string
    {
        $return_var = $this->resolveCommandAllowRootMode($command)
            ->resolveCommandDebugMode($command)
            ->callWpCliCommand($command, $return_script_code);

        if (!$return_script_code) {
            throw new WpCliCommandReturnedNonZero($command, $return_var);
        }

        return $return_var;
    }
}

<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Application\Commands\Traits\AllowRootMode\DTO\AllowRootModeOptionDTO;
use Wordless\Application\Commands\Traits\WunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\WpCliCaller;

trait RunWpCliCommand
{
    use AllowRootMode;

    /**
     * @param string $command
     * @param OutputInterface|null $output
     * @return int|string
     * @throws ExceptionInterface
     */
    private function callWpCliCommand(string $command, ?OutputInterface $output = null): int|string
    {
        return $this->callConsoleCommand(WpCliCaller::COMMAND_NAME, [
            WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
        ], $output);
    }

    private function resolveCommandAllowRootMode(string &$command): static
    {
        if ($this->isAllowRootMode()) {
            $command = "$command --" . AllowRootModeOptionDTO::ALLOW_ROOT_MODE;
        }

        return $this;
    }

    /**
     * @param string $command
     * @return int|string
     * @throws ExceptionInterface
     */
    private function runAndGetWpCliCommandOutput(string $command): int|string
    {
        return $this->resolveCommandAllowRootMode($command)
            ->callWpCliCommand($command);
    }

    /**
     * @param string $command
     * @param bool $return_script_code
     * @return int|string
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function runWpCliCommand(string $command, bool $return_script_code = false): int|string
    {
        $this->resolveCommandAllowRootMode($command);

        if (($return_var = $this->callWpCliCommand($command, $this->output)) && !$return_script_code) {
            throw new WpCliCommandReturnedNonZero($command, $return_var);
        }

        return $return_var;
    }
}

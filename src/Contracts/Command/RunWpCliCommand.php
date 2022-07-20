<?php

namespace Wordless\Contracts\Command;

use Exception;
use Wordless\Commands\WpCliCaller;
use Wordless\Exceptions\WpCliCommandReturnedNonZero;

trait RunWpCliCommand
{
    use AllowRootMode;

    /**
     * @param string $command
     * @return string
     * @throws Exception
     */
    private function runAndGetWpCliCommandOutput(string $command): string
    {
        if ($this->allowRootMode()) {
            $command = "$command --allow-root";
        }

        return $this->executeWordlessCommand(WpCliCaller::COMMAND_NAME, [
            WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
        ]);
    }

    /**
     * @param string $command
     * @param bool $return_script_code
     * @return int
     * @throws WpCliCommandReturnedNonZero
     * @throws Exception
     */
    private function runWpCliCommand(string $command, bool $return_script_code = false): int
    {
        if ($this->allowRootMode()) {
            $command = "$command --allow-root";
        }

        if (($return_var = $this->executeWordlessCommand(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
            ], $this->output)) && !$return_script_code) {
            dump(ROOT_PROJECT_PATH);
            throw new WpCliCommandReturnedNonZero($command, $return_var);
        }

        return $return_var;
    }
}
<?php

namespace Wordless\Contracts;

use Exception;
use Wordless\Commands\WpCliCaller;
use Wordless\Exception\WpCliCommandReturnedNonZero;

trait WordlessCommandRunWpCliCommand
{
    /**
     * @param string $command
     * @param bool $return_script_code
     * @return int
     * @throws WpCliCommandReturnedNonZero
     * @throws Exception
     */
    private function runWpCliCommand(string $command, bool $return_script_code = false): int
    {
        if ($this->modes[self::ALLOW_ROOT_MODE]) {
            $command = "$command --allow-root";
        }

        if (($return_var = $this->executeWordlessCommand(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
            ], $this->output)) && !$return_script_code) {
            throw new WpCliCommandReturnedNonZero($command, $return_var);
        }

        return $return_var;
    }
}
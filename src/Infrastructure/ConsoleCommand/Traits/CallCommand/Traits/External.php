<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;

trait External
{
    /**
     * @param string $full_command
     * @param bool $return_script_result_code
     * @return int|string
     * @throws CliReturnedNonZero
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommand(string $full_command, bool $return_script_result_code = false): int|string
    {
        $process = $this->mountCommandProcess($full_command);

        return $return_script_result_code ?
            $this->runProcessReturningResultCode($process) :
            $this->runProcessReturningOutput($process);
    }

    /**
     * @param string $full_command
     * @return string
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommandReturningOutputWithoutInterruption(string $full_command): string
    {
        try {
            return $this->callExternalCommand($full_command);
        } catch (CliReturnedNonZero $exception) {
            return $exception->script_result_output;
        }
    }

    /**
     * @param string $full_command
     * @return Process
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    private function mountCommandProcess(string $full_command): Process
    {
        return Process::fromShellCommandline($full_command)
            ->setTimeout(null)
            ->setTty(true);
    }

    /**
     * @param Process $process
     * @return int
     * @throws CliReturnedNonZero
     * @throws LogicException
     */
    private function runProcessReturningOutput(Process $process): int
    {
        $script_result_output = '';
        $script_result_code = $process->run(
            function ($type, $buffer) use (&$script_result_output): void {
                if (empty($buffer)) {
                    return;
                }

                $script_result_output .= $buffer;
            }
        );

        if ($script_result_code !== self::SUCCESS) {
            throw new CliReturnedNonZero($process->getCommandLine(), $script_result_code, $script_result_output);
        }

        return $script_result_output;
    }

    /**
     * @param Process $process
     * @return int
     * @throws LogicException
     */
    private function runProcessReturningResultCode(Process $process): int
    {
        return $process->run(
            function ($type, $buffer): void {
                if (empty($buffer)) {
                    return;
                }

                echo $buffer;
            }
        );
    }
}

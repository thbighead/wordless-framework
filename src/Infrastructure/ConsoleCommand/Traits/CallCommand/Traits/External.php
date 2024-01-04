<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;

trait External
{
    /**
     * @param string $full_command
     * @return Response
     * @throws CliReturnedNonZero
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommand(string $full_command): Response
    {
        $process = $this->mountCommandProcess($full_command);

        $commandResponse = new Response($process->run(
            function ($type, $buffer): void {
                if (empty($buffer)) {
                    return;
                }

                echo $buffer;
            }
        ));

        if ($commandResponse->failed()) {
            throw new CliReturnedNonZero($process->getCommandLine(), $commandResponse);
        }

        return $commandResponse;
    }

    /**
     * @param string $full_command
     * @return Response
     * @throws CliReturnedNonZero
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommandSilently(string $full_command): Response
    {
        $process = $this->mountCommandProcess($full_command);
        $command_output = '';

        $commandResponse = new Response($process->run(
            function ($type, $buffer) use (&$command_output): void {
                if (empty($buffer)) {
                    return;
                }

                $command_output .= $buffer;
            }
        ), $command_output);

        if ($commandResponse->failed()) {
            throw new CliReturnedNonZero($process->getCommandLine(), $commandResponse);
        }

        return $commandResponse;
    }

    /**
     * @param string $full_command
     * @return Response
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommandSilentlyWithoutInterruption(string $full_command): Response
    {
        try {
            return $this->callExternalCommandSilently($full_command);
        } catch (CliReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }

    /**
     * @param string $full_command
     * @return Response
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommandWithoutInterruption(string $full_command): Response
    {
        try {
            return $this->callExternalCommand($full_command);
        } catch (CliReturnedNonZero $exception) {
            return $exception->commandResponse;
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
}

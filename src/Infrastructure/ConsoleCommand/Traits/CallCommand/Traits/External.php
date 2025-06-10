<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Symfony\Component\Process\Exception\ProcessStartFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions\CallExternalCommandException;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions\FailedToMountCommandProcessException;

trait External
{
    /**
     * @param string $full_command
     * @param bool $set_tty
     * @return Response
     * @throws CallExternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function callExternalCommand(string $full_command, bool $set_tty = true): Response
    {
        try {
            $process = $this->mountCommandProcess($full_command, $set_tty);

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
        } catch (FailedToMountCommandProcessException|LogicException|ProcessSignaledException|ProcessStartFailedException|ProcessTimedOutException|RuntimeException $exception) {
            throw new CallExternalCommandException($exception);
        }

        return $commandResponse;
    }

    /**
     * @param string $full_command
     * @return Response
     * @throws CallExternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function callExternalCommandSilently(string $full_command): Response
    {
        try {
            $process = $this->mountCommandProcess($full_command, false);
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
        } catch (FailedToMountCommandProcessException|LogicException|ProcessSignaledException|ProcessStartFailedException|ProcessTimedOutException|RuntimeException $exception) {
            throw new CallExternalCommandException($exception);
        }
    }

    /**
     * @param string $full_command
     * @return Response
     * @throws CallExternalCommandException
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
     * @param bool $set_tty
     * @return Response
     * @throws CallExternalCommandException
     */
    protected function callExternalCommandWithoutInterruption(string $full_command, bool $set_tty = true): Response
    {
        try {
            return $this->callExternalCommand($full_command, $set_tty);
        } catch (CliReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }

    /**
     * @param string $full_command
     * @param bool $set_tty
     * @return Process
     * @throws FailedToMountCommandProcessException
     */
    private function mountCommandProcess(string $full_command, bool $set_tty = true): Process
    {
        try {
            return Process::fromShellCommandline($full_command)
                ->setTimeout(null)
                ->setTty($set_tty);
        } catch (InvalidArgumentException|LogicException|RuntimeException $exception) {
            throw new FailedToMountCommandProcessException($exception);
        }
    }
}

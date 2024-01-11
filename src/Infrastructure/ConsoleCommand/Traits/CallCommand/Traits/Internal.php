<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;

trait Internal
{
    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function callConsoleCommand(string $command_name, array $inputs = []): Response
    {
        $consoleResponse = new Response($this->runConsoleCommand(
            $command = $this->getConsoleCommandInstance($command_name),
            $inputs
        ));

        if ($consoleResponse->failed()) {
            throw new CliReturnedNonZero($command, $consoleResponse);
        }

        return $consoleResponse;
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function callConsoleCommandSilently(string $command_name, array $inputs = []): Response
    {
        $consoleResponse = new Response($this->runConsoleCommand(
            $command = $this->getConsoleCommandInstance($command_name),
            $inputs,
            $bufferedOutput = $this->mountBufferedOutput()
        ), $bufferedOutput->fetch());

        if ($consoleResponse->failed()) {
            throw new CliReturnedNonZero($command, $consoleResponse);
        }

        return $consoleResponse;
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function callConsoleCommandSilentlyWithoutInterrupt(string $command_name, array $inputs = []): Response
    {
        try {
            return $this->callConsoleCommandSilently($command_name, $inputs);
        } catch (CliReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function callConsoleCommandWithoutInterrupt(string $command_name, array $inputs = []): Response
    {
        try {
            return $this->callConsoleCommand($command_name, $inputs);
        } catch (CliReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }

    private function mountBufferedOutput(): BufferedOutput
    {
        return new BufferedOutput($this->output->getVerbosity(), true);
    }

    /**
     * @param ConsoleCommand $command
     * @param array $inputs
     * @param OutputInterface|null $output
     * @return int
     * @throws ExceptionInterface
     */
    private function runConsoleCommand(
        ConsoleCommand $command,
        array $inputs = [],
        ?OutputInterface $output = null
    ): int
    {
        return $command->run(new ArrayInput($inputs), $output ?? $this->output);
    }
}

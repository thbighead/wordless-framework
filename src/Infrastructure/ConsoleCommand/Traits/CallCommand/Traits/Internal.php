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
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;

trait Internal
{
    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CallInternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function callConsoleCommand(string $command_name, array $inputs = []): Response
    {
        try {
            $consoleResponse = new Response($this->runConsoleCommand(
                $command = $this->getConsoleCommandInstance($command_name),
                $inputs
            ));

            if ($consoleResponse->failed()) {
                throw new CliReturnedNonZero((string)$command, $consoleResponse);
            }

            return $consoleResponse;
        } catch (CommandNotFoundException|ExceptionInterface $exception) {
            throw new CallInternalCommandException($exception);
        }
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CallInternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function callConsoleCommandSilently(string $command_name, array $inputs = []): Response
    {
        try {
            $consoleResponse = new Response($this->runConsoleCommand(
                $command = $this->getConsoleCommandInstance($command_name),
                $inputs,
                $bufferedOutput = $this->mountBufferedOutput()
            ), $bufferedOutput->fetch());

            if ($consoleResponse->failed()) {
                throw new CliReturnedNonZero((string)$command, $consoleResponse);
            }

            return $consoleResponse;
        } catch (CommandNotFoundException|ExceptionInterface $exception) {
            throw new CallInternalCommandException($exception);
        }
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CallInternalCommandException
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
     * @throws CallInternalCommandException
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
        ConsoleCommand   $command,
        array            $inputs = [],
        ?OutputInterface $output = null
    ): int
    {
        return $command->run(new ArrayInput($inputs), $output ?? $this->output);
    }
}

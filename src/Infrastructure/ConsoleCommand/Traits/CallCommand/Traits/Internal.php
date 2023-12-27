<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand;

trait Internal
{
    /**
     * @param string $command_name
     * @param array $inputs
     * @param bool $return_script_code
     * @return int|string
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function callConsoleCommand(
        string $command_name,
        array  $inputs = [],
        bool   $return_script_code = false
    ): int|string
    {
        $command = $this->getConsoleCommandInstance($command_name);
        $inputs = new ArrayInput($inputs);

        return $return_script_code ?
            $this->runCommandReturningResultCode($command, $inputs) :
            $this->runCommandReturningOutput($command, $inputs);
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return string
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function callConsoleCommandReturningOutputWithoutInterruption(
        string $command_name,
        array  $inputs = []
    ): string
    {
        try {
            return $this->callConsoleCommand($command_name, $inputs);
        } catch (CliReturnedNonZero $exception) {
            return $exception->script_result_output;
        }
    }

    private function mountBufferedOutput(): BufferedOutput
    {
        return new BufferedOutput($this->output->getVerbosity(), true);
    }

    /**
     * @param ConsoleCommand $command
     * @param ArrayInput $inputs
     * @return int
     * @throws CliReturnedNonZero
     * @throws ExceptionInterface
     */
    private function runCommandReturningOutput(ConsoleCommand $command, ArrayInput $inputs): int
    {
        $script_result_code = $command->run($inputs, $bufferedOutput = $this->mountBufferedOutput());
        $script_result_output = $bufferedOutput->fetch();

        if ($script_result_code !== self::SUCCESS) {
            throw new CliReturnedNonZero($command, $script_result_code, $script_result_output);
        }

        return $script_result_output;
    }

    /**
     * @param ConsoleCommand $command
     * @param ArrayInput $inputs
     * @return int
     * @throws ExceptionInterface
     */
    private function runCommandReturningResultCode(ConsoleCommand $command, ArrayInput $inputs): int
    {
        return $command->run($inputs, $this->output);
    }
}

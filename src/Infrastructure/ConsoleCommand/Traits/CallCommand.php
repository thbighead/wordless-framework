<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;

trait CallCommand
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
        return $this->resolveCommandReturn(
            "'php console $command_name " . implode(' ', $inputs) . '\'',
            $return_script_code,
            $this->getConsoleCommandInstance($command_name)
                ->run(
                    new ArrayInput($inputs),
                    $return_script_code ? $this->output : $bufferedOutput = $this->mountBufferedOutput()
                ),
            isset($bufferedOutput) ? $bufferedOutput->fetch() : ''
        );
    }

    /**
     * @param string $full_command
     * @param bool $return_script_code
     * @return int|string
     * @throws CliReturnedNonZero
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommand(string $full_command, bool $return_script_code = false): int|string
    {
        $command_output = '';
        $script_result_code = Process::fromShellCommandline($full_command)
            ->setTimeout(null)
            ->setTty(true)
            ->run(
                function ($type, $buffer) use (&$command_output, $return_script_code): void {
                    if (empty($buffer)) {
                        return;
                    }

                    $command_output .= $buffer;

                    if ($return_script_code) {
                        echo $buffer;
                    }
                }
            );

        return $this->resolveCommandReturn(
            $full_command,
            $return_script_code,
            $script_result_code,
            $command_output
        );
    }

    /**
     * @param string $full_command
     * @param bool $return_script_code
     * @param int $script_result_code
     * @param string $command_output
     * @return int|string
     * @throws CliReturnedNonZero
     */
    private function resolveCommandReturn(
        string $full_command,
        bool   $return_script_code,
        int    $script_result_code,
        string $command_output
    ): int|string
    {
        if ($return_script_code) {
            return $script_result_code;
        }

        if ($script_result_code !== self::SUCCESS) {
            throw new CliReturnedNonZero($full_command, $script_result_code);
        }

        return $command_output;
    }
}

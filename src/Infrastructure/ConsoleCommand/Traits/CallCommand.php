<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;

trait CallCommand
{
    /**
     * @param string $full_command
     * @return int
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    protected function callExternalCommand(string $full_command): int
    {
        if ($this->output instanceof BufferedOutput) {
            exec($full_command, $output, $result_code);
            $this->output->writeln($output);

            return $result_code;
        }

        return Process::fromShellCommandline($full_command)
            ->setTimeout(null)
            ->setTty(true)
            ->run(function ($type, $buffer) {
                echo $buffer;
            });
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @param OutputInterface|null $output
     * @return int|string
     * @throws ExceptionInterface
     */
    protected function callConsoleCommand(
        string           $command_name,
        array            $inputs = [],
        ?OutputInterface $output = null
    ): int|string
    {
        $return_code = $this->resolveOutput($output)
            ->getConsoleCommandInstance($command_name)
            ->run(new ArrayInput($inputs), $output);

        return $output instanceof BufferedOutput ? $output->fetch() : $return_code;
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @param BufferedOutput|null $output
     * @return string
     * @throws ExceptionInterface
     */
    protected function callConsoleCommandGettingOutput(
        string          $command_name,
        array           $inputs = [],
        ?BufferedOutput $output = null
    ): string
    {
        $this->resolveOutput($output)
            ->getConsoleCommandInstance($command_name)
            ->run(new ArrayInput($inputs), $output);

        return $output->fetch();
    }

    private function resolveOutput(?OutputInterface &$output): static
    {
        if (!($output instanceof BufferedOutput)) {
            $output = $this->mountBufferedOutput();
        }

        return $this;
    }
}

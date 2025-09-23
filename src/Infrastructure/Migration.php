<?php declare(strict_types=1);

namespace Wordless\Infrastructure;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Symfony\Component\Process\Exception\ProcessStartFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions\CallExternalCommandException;

abstract class Migration
{
    use External;

    final public const FILENAME_DATE_FORMAT = 'Y_m_d_His_';

    abstract public function up(): void;

    abstract public function down(): void;

    /**
     * @param string $command_name
     * @param array<int|string, string> $inputs
     * @return Response
     * @throws CallExternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function callConsoleCommand(string $command_name, array $inputs = []): Response
    {
        return $this->callExternalCommand($this->mountCommand($command_name, $inputs));
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CallExternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function callConsoleCommandSilently(string $command_name, array $inputs = []): Response
    {
        return $this->callExternalCommandSilently($this->mountCommand($command_name, $inputs));
    }

    /**
     * @param string $command_name
     * @param array $inputs
     * @return Response
     * @throws CallExternalCommandException
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
     * @throws CallExternalCommandException
     */
    protected function callConsoleCommandWithoutInterrupt(string $command_name, array $inputs = []): Response
    {
        try {
            return $this->callConsoleCommand($command_name, $inputs);
        } catch (CliReturnedNonZero $exception) {
            return $exception->commandResponse;
        }
    }

    private function mountCommand(string $command_name, array $inputs = []): string
    {
        return "php console $command_name{$this->parseInputs($inputs)}";
    }

    private function parseArgumentInput(string $argument_value): string
    {
        $argumentValue = Str::of(ltrim(trim($argument_value), '-'));

        if ($argumentValue->isEmpty()) {
            return '';
        }

        return (string)$argumentValue->startWith(' ');
    }

    private function parseInputs(array $inputs): string
    {
        $parsed_arguments = '';
        $parsed_options = '';

        foreach ($inputs as $input_key => $input_value) {
            if (is_int($input_key)) {
                $parsed_arguments .= " {$this->parseArgumentInput((string)$input_value)}";
                continue;
            }

            $parsed_options .= " {$this->parseOptionInput($input_key, (string)$input_value)}";
        }

        return "$parsed_arguments$parsed_options";
    }

    private function parseOptionInput(string $option_key, string $option_value): string
    {
        $optionKey = Str::of(ltrim(trim($option_key), '-'));

        if ($optionKey->isEmpty()) {
            return '';
        }

        $optionKey->startWith($optionKey->length() > 1 ? ' --' : ' -');

        $optionValue = Str::of(ltrim(trim($option_value), '-'));

        if ($optionValue->isEmpty()) {
            return (string)$optionKey;
        }

        return "$optionKey=$optionValue";
    }
}

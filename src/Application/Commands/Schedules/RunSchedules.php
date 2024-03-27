<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Schedules;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Traits\NoTtyMode\DTO\NoTtyModeOptionDTO;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Infrastructure\ConsoleCommand;

class RunSchedules extends ConsoleCommand
{
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'schedule:run';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Runs all cron jobs registered.';
    }


    protected function help(): string
    {
        return 'Checks for Wordpress cron jobs to run throughout WP-CLI command.';
    }

    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption()
        ];
    }

    /**
     * @return int
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        return $this->callConsoleCommand(WpCliCaller::COMMAND_NAME, [
            WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => 'cron event run --due-now',
            '--' . NoTtyModeOptionDTO::NO_TTY_MODE => true,
        ])->result_code;
    }
}

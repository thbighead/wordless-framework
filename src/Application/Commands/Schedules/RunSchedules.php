<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Schedules;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
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
            $this->mountAllowRootModeOption(),
        ];
    }

    /**
     * @return int
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $this->runWpCliCommand('cron event run --due-now');

        return Command::SUCCESS;
    }
}

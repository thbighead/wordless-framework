<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Schedules;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Schedules\ListSchedules\Exceptions\FailedToRunSchedulesListCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Infrastructure\ConsoleCommand;

class ListSchedules extends ConsoleCommand
{
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'schedule:list';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Lists all scheduled cron jobs.';
    }


    protected function help(): string
    {
        return 'Lists all scheduled cron jobs using WP-CLI.';
    }

    protected function options(): array
    {
        return [
            ...$this->mountRunWpCliOptions(),
        ];
    }

    /**
     * @return int
     * @throws FailedToRunSchedulesListCommand
     */
    protected function runIt(): int
    {
        try {
            $this->runWpCliCommand('cron event list');

            return Command::SUCCESS;
        } catch (FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToRunSchedulesListCommand($exception);
        }
    }
}

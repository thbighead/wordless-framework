<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;

class RunSchedules extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'schedule:run';
    private const FILENAME = 'wp-cron.php';

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
        return 'Checks for Wordpress cron jobs to run throughout ' . self::FILENAME . ' requisition.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        require ProjectPath::wpCore(self::FILENAME);

        return Command::SUCCESS;
    }
}

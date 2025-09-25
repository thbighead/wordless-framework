<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Schedules;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Infrastructure\ConsoleCommand;

class RegisterSchedules extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'schedule:register';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Register all Schedule classes into WP cron.';
    }


    protected function help(): string
    {
        return 'All Schedules are registered just once according to their hooks.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws FailedToLoadBootstrapper
     */
    protected function runIt(): int
    {
        Bootstrapper::bootIntoRegisterSchedulesCommand();

        return Command::SUCCESS;
    }
}

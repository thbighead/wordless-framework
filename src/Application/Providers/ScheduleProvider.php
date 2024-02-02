<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Schedules\ListSchedules;
use Wordless\Application\Commands\Schedules\RegisterSchedules;
use Wordless\Application\Commands\Schedules\RunSchedules;
use Wordless\Infrastructure\Provider;

class ScheduleProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            ListSchedules::class,
            RegisterSchedules::class,
            RunSchedules::class,
        ];
    }
}

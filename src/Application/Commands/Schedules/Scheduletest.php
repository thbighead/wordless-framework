<?php

namespace Wordless\Application\Commands\Schedules;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Schedule;
use Wordless\Infrastructure\Wordpress\Schedule\Enums\Recurrence;

class Scheduletest extends Schedule
{

    public static function run(): void
    {
        // TODO: Implement run() method.
    }

    protected static function hook(): ActionHook
    {
        // TODO: Implement hook() method.
    }

    protected static function recurrence(): Recurrence
    {
        // TODO: Implement recurrence() method.
    }
}

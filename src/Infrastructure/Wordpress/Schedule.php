<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Schedule\Contracts\RecurrenceInSeconds;
use Wordless\Infrastructure\Wordpress\Schedule\Enums\StandardRecurrence;

abstract class Schedule
{
    abstract public static function recurrence(): StandardRecurrence|RecurrenceInSeconds;

    abstract public static function run(): void;

    abstract protected static function hook(): ActionHook;

    public static function priority(): int
    {
        return 10;
    }

    public static function registerHook(): void
    {
        add_action(static::hook()->value, [static::class, 'run'], static::priority(), 0);
    }

    public static function schedule(): void
    {
        if (!wp_next_scheduled(static::hook()->value)) {
            wp_schedule_event(time(), static::recurrence()->value, static::hook()->value);
        }
    }
}

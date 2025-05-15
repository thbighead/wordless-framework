<?php /** @noinspection PhpUnused */

declare(strict_types=1);

/**
 * Used just to help IDE to know those constants should be correctly loaded dynamically.
 */

use Wordless\Infrastructure\Wordpress\Hook;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;
use Wordless\Infrastructure\Wordpress\Menu;
use Wordless\Infrastructure\Wordpress\Schedule;
use Wordless\Infrastructure\Wordpress\Schedule\Enums\StandardRecurrence;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\Hook\Enums\Type;

const INTERNAL_WORDLESS_CACHE = [];

final class ExampleAjaxListener extends AjaxListener
{
    protected static function hook(): ActionHook
    {
        return Action::init;
    }

    protected static function isAvailableToAdminPanel(): bool
    {
        return true;
    }

    protected static function isAvailableToFrontend(): bool
    {
        return true;
    }
}

final class ExampleListener extends Listener
{
    protected static function hook(): Hook
    {
        return Action::init;
    }

    protected static function type(): Type
    {
        return Type::action;
    }
}

final class ExampleSchedule extends Schedule
{
    public static function run(): void
    {
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }

    public static function recurrence(): StandardRecurrence
    {
        return StandardRecurrence::daily;
    }
}

final class ExampleMenu extends Menu
{
    public static function id(): string
    {
        return '';
    }

    public static function name(): string
    {
        return '';
    }
}

<?php /** @noinspection PhpUnused */
/** @noinspection PhpIllegalPsrClassPathInspection */

/**
 * Used just to help IDE to know those constants should be correctly loaded dynamically.
 */

use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

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

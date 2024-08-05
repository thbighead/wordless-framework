<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

use Closure;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

abstract class GlobalAjaxListener extends AjaxListener
{
    public static function hookIt(?Closure $callback = null): void
    {
        static::addActionToFrontend($callback);
        static::addActionToAdminPanel($callback);
    }

    final protected static function isAvailableToAdminPanel(): bool
    {
        return true;
    }

    final protected static function isAvailableToFrontend(): bool
    {
        return true;
    }
}

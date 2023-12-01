<?php

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

abstract class GlobalAjaxListener extends AjaxListener
{
    public static function hookIt(): void
    {
        static::addActionToFrontend();
        static::addActionToAdminPanel();
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

<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

abstract class AdminAjaxListener extends AjaxListener
{
    public static function hookIt(): void
    {
        static::addActionToAdminPanel();
    }

    final protected static function isAvailableToAdminPanel(): bool
    {
        return true;
    }

    final protected static function isAvailableToFrontend(): bool
    {
        return false;
    }
}

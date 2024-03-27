<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

abstract class FrontendAjaxListener extends AjaxListener
{
    public static function hookIt(): void
    {
        static::addActionToFrontend();
    }

    final protected static function isAvailableToAdminPanel(): bool
    {
        return false;
    }

    final protected static function isAvailableToFrontend(): bool
    {
        return true;
    }
}

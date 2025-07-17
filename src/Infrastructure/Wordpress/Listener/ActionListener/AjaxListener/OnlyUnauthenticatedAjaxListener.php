<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

use Closure;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener\AjaxListener;

abstract class OnlyUnauthenticatedAjaxListener extends AjaxListener
{
    public static function hookIt(?Closure $callback = null): void
    {
        static::addActionToUnauthenticatedUser($callback);
    }
}

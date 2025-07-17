<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener;

use Closure;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;

abstract class AjaxListener extends ActionListener
{
    private const PREFIX_WP_AJAX = 'wp_ajax_';
    private const PREFIX_WP_AJAX_NOPRIV = 'wp_ajax_nopriv_';

    public static function hookIt(?Closure $callback = null): void
    {
        static::addActionToAuthenticatedUser($callback);
        static::addActionToUnauthenticatedUser($callback);
    }

    final protected static function addActionToAuthenticatedUser(?Closure $callback = null): void
    {
        add_action(
            Str::startWith(static::hook()->value, self::PREFIX_WP_AJAX),
            $callback ?? [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }

    final protected static function addActionToUnauthenticatedUser(?Closure $callback = null): void
    {
        add_action(
            Str::startWith(static::hook()->value, self::PREFIX_WP_AJAX_NOPRIV),
            $callback ?? [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }
}

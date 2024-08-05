<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Listener\ActionListener;

use Closure;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;

abstract class AjaxListener extends ActionListener
{
    /**
     * Define wp_ajax_ prefixed hook to let AJAX be called from admin panel (only logged in)
     *
     * @return bool
     */
    abstract protected static function isAvailableToAdminPanel(): bool;

    /**
     * Define wp_ajax_nopriv_ prefixed hook to let AJAX be called from frontend application (log in not an obligation)
     *
     * @return bool
     */
    abstract protected static function isAvailableToFrontend(): bool;

    final protected const PREFIX_WP_AJAX = 'wp_ajax_';
    final protected const PREFIX_WP_AJAX_NOPRIV = 'wp_ajax_nopriv_';

    public static function hookIt(?Closure $callback = null): void
    {
        if (static::isAvailableToFrontend()) {
            static::addActionToFrontend($callback);
        }

        if (static::isAvailableToAdminPanel()) {
            static::addActionToAdminPanel($callback);
        }
    }

    final protected static function addActionToAdminPanel(?Closure $callback = null): void
    {
        add_action(
            Str::startWith(static::hook()->value, self::PREFIX_WP_AJAX),
            $callback ?? [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }

    final protected static function addActionToFrontend(?Closure $callback = null): void
    {
        add_action(
            Str::startWith(static::hook()->value, self::PREFIX_WP_AJAX_NOPRIV),
            $callback ?? [static::class, static::FUNCTION],
            static::priority(),
            static::functionNumberOfArgumentsAccepted()
        );
    }
}

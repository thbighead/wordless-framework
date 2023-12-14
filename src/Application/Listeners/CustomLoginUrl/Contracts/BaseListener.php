<?php

namespace Wordless\Application\Listeners\CustomLoginUrl\Contracts;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener;

abstract class BaseListener extends Listener
{
    final public const WP_CUSTOM_LOGIN_URL_KEY = 'wp_custom_login_url';
    final public const WP_REDIRECT_URL_KEY = 'wp_redirect_url';
    /**
     * The function which shall be executed during hook
     */
    final protected const FUNCTION = 'load';

    /**
     * @return string|false
     * @throws PathNotFoundException
     */
    protected static function canHook(): string|false
    {
        return Config::tryToGetOrDefault('wordpress.admin.' . static::WP_CUSTOM_LOGIN_URL_KEY, false);
    }

    /**
     * @param $url
     * @param $scheme
     * @return string
     * @throws PathNotFoundException
     */
    protected static function filterWpLoginPhp($url, $scheme = null): string
    {
        if (Str::contains($url, 'wp-login.php')) {
            if (is_ssl()) {
                $scheme = 'https';
            }

            $args = explode('?', $url);

            if (isset($args[1])) {
                parse_str($args[1], $args);

                return add_query_arg($args, self::newLoginUrl($scheme));
            }

            return self::newLoginUrl($scheme);
        }

        return $url;
    }

    /**
     * @return string|false
     * @throws PathNotFoundException
     */
    protected static function newLoginSlug(): string|false
    {
        return Config::tryToGetOrDefault('wordpress.admin.' . static::WP_CUSTOM_LOGIN_URL_KEY, false);
    }

    /**
     * @param $scheme
     * @return string
     * @throws PathNotFoundException
     */
    protected static function newLoginUrl($scheme = null): string
    {
        if (get_option('permalink_structure')) {
            return user_trailingslashit(home_url('/', $scheme) . self::newLoginSlug());
        }

        return home_url('/', $scheme) . '?' . self::newLoginSlug();
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    protected static function newRedirectUrl(): bool
    {
        return Config::tryToGetOrDefault('wordpress.admin.' . self::WP_REDIRECT_URL_KEY, false);
    }
}

<?php

namespace Wordless\Application\Listeners\CustomLoginUrl\Traits;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;

trait Common
{
    public const WP_CUSTOM_LOGIN_URL = 'wp_custom_login_url';
    public const WP_REDIRECT_URL = 'wp_redirect_url';

    /**
     * @return string|false
     * @throws PathNotFoundException
     */
    protected static function canHook(): string|false
    {
        return Config::tryToGetOrDefault('wordpress.admin.' . static::WP_CUSTOM_LOGIN_URL, false);
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
        return Config::tryToGetOrDefault('wordpress.admin.' . static::WP_CUSTOM_LOGIN_URL, false);
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
        return Config::tryToGetOrDefault('wordpress.admin.' . self::WP_REDIRECT_URL, false);
    }
}

<?php

namespace Wordless\Hookers\CustomLoginUrl;

use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;

class CustomLoginUrlHooker extends Hooker
{

    public const WP_CUSTOM_LOGIN_URL = 'wp_custom_login_url';
    public const WP_REDIRECT_URL = 'wp_redirect_url';

    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 10;

    /**
     * @throws PathNotFoundException
     */
    protected static function canHook()
    {
        return Config::tryToGetOrDefault('admin.' . self::WP_CUSTOM_LOGIN_URL, false);
    }

    protected static function filterWpLoginPhp($url, $scheme = null): string
    {
        if (strpos($url, 'wp-login.php') !== false) {
            if (is_ssl()) {
                $scheme = 'https';
            }

            $args = explode('?', $url);

            if (isset($args[1])) {
                parse_str($args[1], $args);
                $url = add_query_arg($args, self::newLoginUrl($scheme));
            } else {
                $url = self::newLoginUrl($scheme);
            }
        }
        return $url;
    }

    /**
     * @throws PathNotFoundException
     */
    protected static function newLoginSlug()
    {
        return Config::tryToGetOrDefault('admin.' . self::WP_CUSTOM_LOGIN_URL, false);
    }

    protected static function newLoginUrl($scheme = null): string
    {
        if (get_option('permalink_structure')) {
            return user_trailingslashit(home_url('/', $scheme) . self::newLoginSlug());
        } else {
            return home_url('/', $scheme) . '?' . self::newLoginSlug();
        }
    }

    /**
     * @throws PathNotFoundException
     */
    protected static function newRedirectUrl()
    {
        return Config::tryToGetOrDefault('admin.' . self::WP_REDIRECT_URL, false);
    }
}

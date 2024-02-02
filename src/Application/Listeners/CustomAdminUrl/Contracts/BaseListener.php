<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomAdminUrl\Contracts;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener;

abstract class BaseListener extends Listener
{
    final public const CUSTOM_ADMIN_URI_KEY = 'custom_admin_url';
    final public const REDIRECT_FROM_DEFAULTS_TO_URL_KEY = 'redirect_from_defaults_to_url';
    /**
     * The function which shall be executed during hook
     */
    final protected const FUNCTION = 'load';
    private const CONFIG_PREFIX = 'wordpress.admin.';
    private const DEFAULT_CUSTOM_ADMIN_URI = 'wordless';

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    protected static function canHook(): bool
    {
        return !empty((string)static::getConfig()->get(self::CUSTOM_ADMIN_URI_KEY));
    }

    protected static function getConfig(): ConfigSubjectDTO
    {
        return Config::of(self::CONFIG_PREFIX);
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
        return Config::get(self::CONFIG_PREFIX . static::CUSTOM_ADMIN_URI_KEY, false);
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
     * @return string
     * @throws PathNotFoundException
     */
    protected static function newRedirectUrl(): string
    {
        $configured_custom_url = (string)Config::get(
            self::CONFIG_PREFIX . self::REDIRECT_FROM_DEFAULTS_TO_URL_KEY
        );

        return empty($configured_custom_url) ? self::DEFAULT_CUSTOM_ADMIN_URI : $configured_custom_url;
    }
}

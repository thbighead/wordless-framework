<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomAdminUrl\Contracts;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener;

abstract class BaseListener extends Listener
{
    final public const CUSTOM_ADMIN_URI_KEY = 'custom_admin_url';
    final public const REDIRECT_FROM_DEFAULTS_TO_URI_KEY = 'redirect_from_defaults_to_url';
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
        return self::getCustomAdminUri() !== null;
    }

    protected static function getConfig(): ConfigSubjectDTO
    {
        return Config::of(self::CONFIG_PREFIX);
    }

    /**
     * @param string $url
     * @param $scheme
     * @return string
     * @throws PathNotFoundException
     */
    protected static function filterWpLoginPhp(string $url, $scheme = null): string
    {
        if (!Str::contains($url, 'wp-login')) {
            return $url;
        }

        if (is_ssl()) {
            $scheme = 'https';
        }

        if (!empty($url_query_parameters_string = Str::after($url, '?'))) {
            $url_query_parameters_array = [];

            parse_str($url_query_parameters_string, $url_query_parameters_array);

            return add_query_arg($url_query_parameters_array, self::newLoginUrl($scheme));
        }

        return self::newLoginUrl($scheme);

    }

    /**
     * @return string|null
     * @throws PathNotFoundException
     */
    protected static function getCustomAdminUri(): ?string
    {
        $custom_login_uri = static::getConfig()->get(static::CUSTOM_ADMIN_URI_KEY);

        return empty($custom_login_uri) ? null : $custom_login_uri;
    }

    /**
     * @param string|null $scheme
     * @return string
     * @throws PathNotFoundException
     */
    protected static function newLoginUrl(?string $scheme = null): string
    {
        if (Option::get('permalink_structure')) {
            return user_trailingslashit(home_url(self::getCustomAdminUri(), $scheme));
        }

        return home_url('/', $scheme) . '?' . self::getCustomAdminUri();
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    protected static function newRedirectUrl(): string
    {
        $configured_custom_url = (string)Config::get(
            self::CONFIG_PREFIX . self::REDIRECT_FROM_DEFAULTS_TO_URI_KEY
        );

        return empty($configured_custom_url) ? self::DEFAULT_CUSTOM_ADMIN_URI : $configured_custom_url;
    }
}

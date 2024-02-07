<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\CustomAdminUrl\Contracts;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener;

abstract class BaseListener extends Listener
{
    final public const CONFIG_KEY_CUSTOM_ADMIN_URI = 'custom_admin_uri';
    final public const CONFIG_KEY_REDIRECT_FROM_DEFAULTS_TO_URI = 'redirect_from_defaults_to_uri';
    /**
     * The function which shall be executed during hook
     */
    final protected const FUNCTION = 'load';
    private const DEFAULT_CUSTOM_ADMIN_URI = 'wordless';

    /**
     * @return bool
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    protected static function canHook(): bool
    {
        return self::getCustomAdminUri() !== null;
    }

    /**
     * @return ConfigSubjectDTO
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    protected static function getConfig(): ConfigSubjectDTO
    {
        return Config::wordpressAdmin();
    }

    /**
     * @param string $url
     * @param $scheme
     * @return string
     * @throws EmptyConfigKey
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
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    protected static function getCustomAdminUri(): ?string
    {
        $custom_login_uri = static::getConfig()->get(static::CONFIG_KEY_CUSTOM_ADMIN_URI);

        return empty($custom_login_uri) ? null : $custom_login_uri;
    }

    /**
     * @param string|null $scheme
     * @return string
     * @throws EmptyConfigKey
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
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    protected static function newRedirectUrl(): string
    {
        $configured_custom_url = (string)static::getConfig()
            ->get(self::CONFIG_KEY_REDIRECT_FROM_DEFAULTS_TO_URI);

        return empty($configured_custom_url) ? self::DEFAULT_CUSTOM_ADMIN_URI : $configured_custom_url;
    }
}

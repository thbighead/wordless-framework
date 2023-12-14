<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Url\Traits\Internal;
use Wordless\Application\Listeners\CustomLoginUrl\Contracts\BaseListener;

class Url
{
    use Internal;

    final public const REST_API_ROUTE_QUERY_PARAMETER = 'rest_route';

    public static function current(bool $with_parameters = false): ?string
    {
        if (($current_uri = static::currentUri($with_parameters)) === null) {
            return null;
        }

        return home_url($current_uri);
    }

    public static function currentUri(bool $with_parameters = false): ?string
    {
        if (empty($request_uri = $_SERVER['REQUEST_URI'] ?? null)) {
            return null;
        }

        return $with_parameters ? $request_uri : Str::before($request_uri, '?');
    }

    public static function getCurrentRestApiEndpoint(): ?string
    {
        if (static::isCurrentRestApiUsingWpJsonRoute()) {
            return Str::after(static::currentUri(), rest_get_url_prefix());
        }

        if (static::isCurrentRestApiUsingQueryParameter()) {
            return $_REQUEST[self::REST_API_ROUTE_QUERY_PARAMETER] ?? null;
        }

        return null;
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    public static function isCurrentAdminLogin(): bool
    {
        $custom_admin_login_uri = Config::tryToGetOrDefault(
            'wordpress.admin.' . BaseListener::WP_CUSTOM_LOGIN_URL_KEY
        );

        if (empty($custom_admin_login_uri)) {
            return isset($_SERVER['SCRIPT_NAME']) && stripos(wp_login_url(), $_SERVER['SCRIPT_NAME']) !== false;
        }

        return static::isCurrentUri($custom_admin_login_uri);
    }

    public static function isCurrentRestApi(): bool
    {
        return static::isCurrentRestApiUsingWpJsonRoute() || static::isCurrentRestApiUsingQueryParameter();
    }

    public static function isCurrentRestApiUsingQueryParameter(): bool
    {
        return isset($_REQUEST[self::REST_API_ROUTE_QUERY_PARAMETER]);
    }

    public static function isCurrentRestApiUsingWpJsonRoute(): bool
    {
        return Str::beginsWith(static::currentUri(), self::SLASH . rest_get_url_prefix());
    }

    public static function isCurrentUri(string $uri): bool
    {
        return trim(Url::currentUri(), self::SLASH) === trim($uri, self::SLASH);
    }

    public static function isUserRestApiRoute(): bool
    {
        return static::isRestApiRoute('users?');
    }

    public static function isRestApiRoute(string $uri_pattern): bool
    {
        if (($uri = static::getCurrentRestApiEndpoint()) === null) {
            return false;
        }

        $is_regex = Str::isSurroundedBy($uri_pattern, self::SLASH, self::SLASH);

        return preg_match($is_regex ? $uri_pattern : "/\/$uri_pattern(\/|$)/", $uri) === 1;
    }

    public static function mountRestApiEndpointUrl(string $uri = self::SLASH): string
    {
        return get_rest_url(null, $uri);
    }
}

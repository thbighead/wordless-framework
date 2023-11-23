<?php

namespace Wordless\Helpers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Hookers\CustomLoginUrl\CustomLoginUrlHooker;

class Url
{
    public const REST_API_ROUTE_QUERY_PARAMETER = 'rest_route';

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

    public static function home(?string $additional_path = null): string
    {
        if (function_exists('home_url')) {
            return home_url($additional_path ?? '');
        }

        return Environment::get('APP_URL') . (
            $additional_path === null ? '' : Str::startWith($additional_path, '/')
            );
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    public static function isCurrentAdminLogin(): bool
    {
        $custom_admin_login_uri = Config::tryToGetOrDefault('admin.' . CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL);

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
        return Str::beginsWith(static::currentUri(), '/' . rest_get_url_prefix());
    }

    public static function isCurrentUri(string $uri): bool
    {
        return trim(Url::currentUri(), '/') === trim($uri, '/');
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

        $is_regex = Str::isSurroundedBy($uri_pattern, '/', '/');

        return preg_match($is_regex ? $uri_pattern : "/\/$uri_pattern(\/|$)/", $uri) === 1;
    }

    public static function mountRestApiEndpointUrl(string $uri = '/'): string
    {
        return get_rest_url(null, $uri);
    }
}

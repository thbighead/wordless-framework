<?php

namespace Wordless\Helpers;

class Url
{
    public const REST_API_ROUTE_QUERY_PARAMETER = 'rest_route';

    public static function current(bool $with_parameters = false): ?string
    {
        if (($current_uri = self::currentUri($with_parameters)) === null) {
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
        if (self::isCurrentRestApiUsingWpJsonRoute()) {
            return Str::after(self::currentUri(), rest_get_url_prefix());
        }

        if (self::isCurrentRestApiUsingQueryParameter()) {
            return $_REQUEST[self::REST_API_ROUTE_QUERY_PARAMETER] ?? null;
        }

        return null;
    }

    public static function isCurrentRestApi(): bool
    {
        return self::isCurrentRestApiUsingWpJsonRoute() || self::isCurrentRestApiUsingQueryParameter();
    }

    public static function isCurrentRestApiUsingQueryParameter(): bool
    {
        return isset($_REQUEST[self::REST_API_ROUTE_QUERY_PARAMETER]);
    }

    public static function isCurrentRestApiUsingWpJsonRoute(): bool
    {
        return Str::beginsWith(self::currentUri(), '/' . rest_get_url_prefix());
    }

    public static function isUserRestApiRoute(): bool
    {
        return self::isRestApiRoute('users?');
    }

    public static function isRestApiRoute(string $uri_pattern): bool
    {
        if (($uri = self::getCurrentRestApiEndpoint()) === null) {
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

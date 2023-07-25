<?php

namespace Wordless\Helpers;

class Url
{
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

    public static function isUserRestApiRoute(): bool
    {
        return preg_match('/\/users?(\/|$)/', Str::after(self::current(), self::restApiBaseUrl())) === 1;
    }

    public static function restApiBaseUrl(): string
    {
        return home_url(rest_get_url_prefix());
    }
}

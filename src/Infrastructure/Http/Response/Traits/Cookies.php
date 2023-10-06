<?php

namespace Wordless\Infrastructure\Http\Response\Traits;

use Requests_Cookie_Jar;
use WP_Http_Cookie;
use WP_HTTP_Requests_Response;

trait Cookies
{
    public function cookies(bool $as_array = true): array|Requests_Cookie_Jar|null
    {
        if (!$as_array) {
            return $this->retrieveOriginalCookiesJar() ?? $this->retrieveOriginalCookiesArray() ?? [];
        }

        if (!empty($cookies = $this->retrieveOriginalCookiesArray())) {
            return $cookies;
        }

        return $this->retrieveOriginalCookiesJar() ?? [];
    }

    /**
     * @return WP_Http_Cookie[]|null
     */
    private function retrieveOriginalCookiesArray(): ?array
    {
        return $this->raw_response['cookies'] ?? null;
    }

    private function retrieveOriginalCookiesJar(): ?Requests_Cookie_Jar
    {
        $originalResponse = $this->raw_response['http_response'] ?? null;

        if (!($originalResponse instanceof WP_HTTP_Requests_Response)) {
            return null;
        }

        $originalCookiesJar = $originalResponse->get_response_object()->cookies;

        return $originalCookiesJar instanceof Requests_Cookie_Jar ? $originalCookiesJar : null;
    }
}

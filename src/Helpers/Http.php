<?php

namespace Wordless\Helpers;

use Symfony\Component\HttpFoundation\Request;
use Wordless\Exceptions\HttpRequestFailed;
use WP_Error;
use WP_Http;

class Http
{
    public const ACCEPT = 'Accept';
    public const CONTENT_TYPE = 'Content-Type';
    public const CONTENT_TYPE_APPLICATION_JSON = 'application/json';
    public const TIMEOUT = 30; // seconds

    /**
     * Singleton
     * @var WP_Http $wp_http
     */
    private static WP_Http $wp_http;

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     * @throws HttpRequestFailed
     */
    public static function delete(string $endpoint, array $body = [], array $headers = []): array
    {
        return self::request(Request::METHOD_DELETE, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     * @throws HttpRequestFailed
     */
    public static function get(string $endpoint, array $body = [], array $headers = []): array
    {
        return self::request(Request::METHOD_GET, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     * @throws HttpRequestFailed
     */
    public static function patch(string $endpoint, array $body = [], array $headers = []): array
    {
        return self::request(Request::METHOD_PATCH, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     * @throws HttpRequestFailed
     */
    public static function post(string $endpoint, array $body = [], array $headers = []): array
    {
        return self::request(Request::METHOD_POST, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     * @throws HttpRequestFailed
     */
    public static function put(string $endpoint, array $body = [], array $headers = []): array
    {
        return self::request(Request::METHOD_PUT, $endpoint, $body, $headers);
    }

    /**
     * @param string $http_method
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     * @throws HttpRequestFailed
     */
    public static function request(
        string $http_method,
        string $endpoint,
        array  $body = [],
        array  $headers = []
    ): array
    {
        $response = self::getWpHttp()->request($endpoint, wp_parse_args([
            'method' => $http_method,
            'headers' => $headers,
            'body' => str_contains(($headers[self::CONTENT_TYPE] ?? ''), self::CONTENT_TYPE_APPLICATION_JSON) ?
                json_encode($body) : $body,
            'timeout' => self::TIMEOUT,
        ]));

        if ($response instanceof WP_Error) {
            throw new HttpRequestFailed($response);
        }

        if (is_string($response['body'] ?? null)) {
            if (!str_contains(($headers[self::ACCEPT] ?? ''), self::CONTENT_TYPE_APPLICATION_JSON)) {
                $response['original_body'] = $response['body'];
            }
            $response['body'] = json_decode($response['body'], true);
        }

        return $response;
    }

    private static function getWpHttp(): WP_Http
    {
        if (isset(static::$wp_http)) {
            return static::$wp_http;
        }

        return static::$wp_http = new WP_Http;
    }
}

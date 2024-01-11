<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Symfony\Component\HttpFoundation\Request;
use Wordless\Application\Helpers\Http\Exceptions\RequestFailed;
use Wordless\Application\Helpers\Http\Traits\Internal;
use Wordless\Infrastructure\Http\Request\Enums\Verb;
use Wordless\Infrastructure\Http\Response;
use WP_Error;

class Http
{
    use Internal;

    final public const ACCEPT = 'Accept';
    final public const BODY = 'body';
    final public const CONTENT_TYPE = 'Content-Type';
    final public const CONTENT_TYPE_APPLICATION_JSON = 'application/json';
    final public const TIMEOUT = 30; // seconds

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return Response
     * @throws RequestFailed
     */
    public static function delete(string $endpoint, array $body = [], array $headers = []): Response
    {
        return static::request(Verb::delete, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return Response
     * @throws RequestFailed
     */
    public static function get(string $endpoint, array $body = [], array $headers = []): Response
    {
        return static::request(Verb::get, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return Response
     * @throws RequestFailed
     */
    public static function patch(string $endpoint, array $body = [], array $headers = []): Response
    {
        return static::request(Verb::patch, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return Response
     * @throws RequestFailed
     */
    public static function post(string $endpoint, array $body = [], array $headers = []): Response
    {
        return static::request(Verb::post, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return Response
     * @throws RequestFailed
     */
    public static function put(string $endpoint, array $body = [], array $headers = []): Response
    {
        return static::request(Verb::put, $endpoint, $body, $headers);
    }

    /**
     * @param Verb $httpVerb
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @param bool|null $only_with_ssl
     * @return Response
     * @throws RequestFailed
     */
    public static function request(
        Verb $httpVerb,
        string $endpoint,
        array  $body = [],
        array  $headers = [],
        ?bool  $only_with_ssl = null,
    ): Response
    {
        $response = self::getWpHttp()->request($endpoint, wp_parse_args([
            'method' => $httpVerb->value,
            'headers' => $headers,
            self::BODY => str_contains(($headers[static::CONTENT_TYPE] ?? ''), static::CONTENT_TYPE_APPLICATION_JSON) ?
                json_encode($body) : $body,
            'timeout' => static::TIMEOUT,
            'sslverify' => $only_with_ssl ?? Environment::isProduction(),
        ]));

        if ($response instanceof WP_Error) {
            throw new RequestFailed($response);
        }

        if (is_string($response[self::BODY] ?? false)) {
            if (!Str::contains(($headers[static::ACCEPT] ?? ''), static::CONTENT_TYPE_APPLICATION_JSON)) {
                $response['original_body'] = $response[self::BODY];
            }

            $response[self::BODY] = json_decode($response[self::BODY], true);
        }

        return new Response($response);
    }
}

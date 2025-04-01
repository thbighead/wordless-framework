<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers;

use JsonException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Http\Contracts\Subjectable;
use Wordless\Application\Helpers\Http\Enums\Version;
use Wordless\Application\Helpers\Http\Exceptions\RequestFailed;
use Wordless\Application\Helpers\Http\Traits\Internal;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Http\Request\Enums\Verb;
use Wordless\Infrastructure\Http\Response;
use WP_Error;

class Http extends Subjectable
{
    use Internal;

    public const TIMEOUT = 30; // seconds
    final public const ACCEPT = 'Accept';
    final public const BODY = 'body';
    final public const CONTENT_TYPE = 'Content-Type';

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $headers
     * @param Version $http_version
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws RequestFailed
     */
    public static function delete(
        string  $endpoint,
        array   $body = [],
        array   $headers = [],
        Version $http_version = Version::http_1_0
    ): Response
    {
        return static::request(Verb::delete, $endpoint, $body, $headers, http_version: $http_version);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $headers
     * @param Version $http_version
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws RequestFailed
     */
    public static function get(
        string  $endpoint,
        array   $body = [],
        array   $headers = [],
        Version $http_version = Version::http_1_0
    ): Response
    {
        return static::request(Verb::get, $endpoint, $body, $headers, http_version: $http_version);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $headers
     * @param Version $http_version
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws RequestFailed
     */
    public static function patch(
        string  $endpoint,
        array   $body = [],
        array   $headers = [],
        Version $http_version = Version::http_1_0
    ): Response
    {
        return static::request(Verb::patch, $endpoint, $body, $headers, http_version: $http_version);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $headers
     * @param Version $http_version
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws RequestFailed
     */
    public static function post(
        string  $endpoint,
        array   $body = [],
        array   $headers = [],
        Version $http_version = Version::http_1_0
    ): Response
    {
        return static::request(Verb::post, $endpoint, $body, $headers, http_version: $http_version);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $headers
     * @param Version $http_version
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws RequestFailed
     */
    public static function put(
        string  $endpoint,
        array   $body = [],
        array   $headers = [],
        Version $http_version = Version::http_1_0
    ): Response
    {
        return static::request(Verb::put, $endpoint, $body, $headers, http_version: $http_version);
    }

    /**
     * @param Verb $httpVerb
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $headers
     * @param bool|null $only_with_ssl
     * @param Version $http_version
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws RequestFailed
     */
    public static function request(
        Verb         $httpVerb,
        string       $endpoint,
        array|string $body = [],
        array        $headers = [],
        ?bool        $only_with_ssl = null,
        Version      $http_version = Version::http_1_0,
    ): Response
    {
        $response = self::getWpHttp()->request($endpoint, wp_parse_args([
            'method' => $httpVerb->value,
            'headers' => $headers,
            self::BODY => $body,
            'timeout' => static::TIMEOUT,
            'sslverify' => $only_with_ssl ?? Environment::isProduction(),
            'httpversion' => $http_version->value,
        ]));

        if ($response instanceof WP_Error) {
            throw new RequestFailed($response);
        }

        self::prepareResponseBody($response, $headers);

        return new Response($response);
    }
}

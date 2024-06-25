<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Traits;

use JsonException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Enums\MimeType;
use WP_Http;

trait Internal
{
    private static WP_Http $wp_http;

    private static function getWpHttp(): WP_Http
    {
        if (isset(self::$wp_http)) {
            return self::$wp_http;
        }

        return self::$wp_http = new WP_Http;
    }

    /**
     * @param array|string $body
     * @param array $headers
     * @return array|string
     * @throws JsonException
     */
    private static function prepareRequestBody(array|string $body, array &$headers): array|string
    {
        if (isset($headers[static::CONTENT_TYPE])) {
            return $body;
        }

        if (is_array($body)) {
            $body = Arr::toJson($body);
        }

        if (Str::isJson($body)) {
            $headers[static::CONTENT_TYPE] = MimeType::application_json->value;
        }

        return $body;
    }

    /**
     * @param array $response
     * @param array $headers
     * @return void
     * @throws JsonException
     */
    private static function prepareResponseBody(array &$response, array $headers): void
    {
        $response_body = $response[self::BODY] ?? '';

        if (!is_string($response_body) || !Str::isJson($response_body)) {
            return;
        }

        if (!Str::contains(($headers[self::ACCEPT] ?? ''), MimeType::application_json->value)) {
            $response['original_body'] = $response[self::BODY];
        }

        $response[self::BODY] = Str::jsonDecode($response[self::BODY]);
    }
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Traits;

use JsonException;
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

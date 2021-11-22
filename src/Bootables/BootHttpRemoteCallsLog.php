<?php

namespace Wordless\Bootables;

use Wordless\Abstractions\AbstractBootable;
use Wordless\Helpers\Environment;
use Wordless\Helpers\Str;

class BootHttpRemoteCallsLog extends AbstractBootable
{
    public static function register()
    {
        if (WP_DEBUG) {
            add_action('http_api_debug', [self::class, 'debugWordPressRemoteRequest'], 10, 5);
        }
    }

    public static function debugWordPressRemoteRequest($response, $context, $class, $r, $url)
    {
        if (Str::beginsWith($url, Environment::get('APP_URL'))) {
            $request = json_encode([
                'http_method' => $r['method'] ?? null,
                'headers' => $r['headers'] ?? null,
                'body' => is_string($body = ($r['body'] ?? null)) ? json_decode($r['body'], true) : $body,
            ], JSON_PRETTY_PRINT);
            $http_response = $response['http_response'];

            if (!($http_response instanceof WP_HTTP_Requests_Response)) {
                $raw_response = 'INVALID RESPONSE OBJECT STRUCTURE: ' . var_export($http_response, true);
            } else {
                $raw_response = $response['http_response']->get_response_object()->raw;
            }

            error_log(self::headerLog('REMOTE API CALL')
                . "> URL: $url\n> REQUEST:\n$request\n> RESPONSE:\n$raw_response"
                . self::headerLog('REMOTE API CALL END'));
        }
    }

    private static function headerLog(string $title): string
    {
        return "\n------------ $title ------------\n";
    }
}
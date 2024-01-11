<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_Error;
use WP_HTTP_Requests_Response;

class BootHttpRemoteCallsLog extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'debugWordPressRemoteRequest';

    /** @noinspection PhpUnusedParameterInspection */
    public static function debugWordPressRemoteRequest($response, $context, $class, $request, $url): void
    {
        if (WP_DEBUG && Str::beginsWith($url, Environment::get('APP_URL'))) {
            $request_as_json = json_encode([
                'http_method' => $request['method'] ?? null,
                'headers' => $request['headers'] ?? null,
                'body' => is_string($body = ($request['body'] ?? null)) ?
                    json_decode($request['body'], true) : $body,
            ], JSON_PRETTY_PRINT);
            $http_response = ($response instanceof WP_Error) ?
                $response->get_error_code() : ($response['http_response'] ?? null);

            if (!($http_response instanceof WP_HTTP_Requests_Response)) {
                $raw_response = 'INVALID RESPONSE OBJECT STRUCTURE: ' . var_export($http_response, true);
            } else {
                $raw_response = $http_response->get_response_object()->raw;
            }

            error_log(self::headerLog('REMOTE API CALL')
                . "> URL: $url\n> REQUEST:\n$request_as_json\n> RESPONSE:\n$raw_response"
                . self::headerLog('REMOTE API CALL END'));
        }
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 5;
    }

    protected static function hook(): ActionHook
    {
        return Action::http_api_debug;
    }

    private static function headerLog(string $title): string
    {
        return "\n------------ $title ------------\n";
    }
}

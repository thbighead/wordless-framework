<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_Error;
use WP_HTTP_Requests_Response;

class BootHttpRemoteCallsLog extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'debugWordPressRemoteRequest';

    /**
     * @param WP_Error|array $response
     * @param mixed $context
     * @param mixed $class
     * @param array $request
     * @param string $url
     * @return void
     * @throws FormatException
     * @throws DotEnvNotSetException
     * @noinspection PhpUnusedParameterInspection
     */
    public static function debugWordPressRemoteRequest(
        WP_Error|array $response,
        mixed          $context,
        mixed          $class,
        array          $request,
        string         $url
    ): void
    {
        if (WP_DEBUG && Str::beginsWith($url, Environment::get('APP_URL', ''))) {
            $request_as_json = json_encode([
                'http_method' => $request['method'] ?? null,
                'headers' => $request['headers'] ?? null,
                'body' => is_string($body = ($request['body'] ?? null)) ?
                    json_decode($request['body'], true) : $body,
            ], JSON_PRETTY_PRINT);
            $http_response = $response instanceof WP_Error ?
                $response->get_error_code() : ($response['http_response'] ?? null);
            $raw_response = $http_response instanceof WP_HTTP_Requests_Response ?
                $http_response->get_response_object()->raw :
                'INVALID RESPONSE OBJECT STRUCTURE: ' . var_export($http_response, true);

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

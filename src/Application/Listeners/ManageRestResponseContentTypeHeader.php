<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use InvalidArgumentException;
use Wordless\Application\Helpers\Http;
use Wordless\Infrastructure\Enums\MimeType;
use Wordless\Infrastructure\Wordpress\ApiController\Request;
use Wordless\Infrastructure\Wordpress\ApiController\Response;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;
use WP_HTTP_Response;

class ManageRestResponseContentTypeHeader extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'avoidJsonEncodeWhenContentTypeIsNotApplicationJson';

    /**
     * @param bool $served
     * @param WP_HTTP_Response $result
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function avoidJsonEncodeWhenContentTypeIsNotApplicationJson(
        bool             $served,
        WP_HTTP_Response $result
    ): bool
    {
        if (!Request::isToRestApi() || self::isResponseContentTypeApplicationJson($result)) {
            return $served;
        }

        // avoids automagic json_encode
        return true;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 2;
    }

    protected static function hook(): FilterHook
    {
        return Filter::rest_pre_serve_request;
    }

    /**
     * @param WP_HTTP_Response $response
     * @return bool
     * @throws InvalidArgumentException
     */
    private static function isResponseContentTypeApplicationJson(WP_HTTP_Response $response): bool
    {
        $response_content_type = $response->get_headers()[Response::canonicalizeHeaderName(Http::CONTENT_TYPE)] ??
            MimeType::application_json->value;
        return $response_content_type === MimeType::application_json->value;
    }

}

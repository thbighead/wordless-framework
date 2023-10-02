<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Http;
use Wordless\Infrastructure\Http\Request;
use Wordless\Infrastructure\Http\Response;
use Wordless\Infrastructure\Wordpress\Listener;
use WP_HTTP_Response;

class ManageRestResponseContentTypeHeader extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 2;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'avoidJsonEncodeWhenContentTypeIsNotApplicationJson';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_pre_serve_request';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

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

    private static function isResponseContentTypeApplicationJson(WP_HTTP_Response $response): bool
    {
        $response_content_type = $response->get_headers()[Response::canonicalizeHeaderName(Http::CONTENT_TYPE)] ??
            Http::CONTENT_TYPE_APPLICATION_JSON;
        return $response_content_type === Http::CONTENT_TYPE_APPLICATION_JSON;
    }
}

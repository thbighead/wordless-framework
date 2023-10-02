<?php

namespace Wordless\Application\Listeners\RestApi;

use Symfony\Component\HttpFoundation\Response;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Url;
use Wordless\Infrastructure\Wordpress\Listener;
use WP_Error;

class Authentication extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'checkUserAuthorization';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_authentication_errors';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    /**
     * @param WP_Error|null|true $errors
     * @return WP_Error|null|true
     * @throws PathNotFoundException
     */
    public static function checkUserAuthorization(WP_Error|null|true $errors): WP_Error|null|true
    {
        if (self::isUnauthorized()) {
            return new WP_Error(
                $code = Response::HTTP_UNAUTHORIZED,
                __('Unauthorized to access route.'),
                ['status' => $code]
            );
        }

        return $errors;
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    private static function isUnauthorized(): bool
    {
        if (self::isCurrentApiEndpointPublic()) {
            return false;
        }

        if (is_user_logged_in()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    private static function isCurrentApiEndpointPublic(): bool
    {
        $public_endpoints = Config::tryToGetOrDefault('rest-api.routes.public', []);
        $current_endpoint = Url::getCurrentRestApiEndpoint();

        foreach ($public_endpoints as $public_endpoint) {
            $regex = Str::replace($public_endpoint, '/', '\/');

            if (preg_match("/$regex/", $current_endpoint)) {
                return true;
            }
        }

        return false;
    }
}

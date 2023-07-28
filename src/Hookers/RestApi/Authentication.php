<?php

namespace Wordless\Hookers\RestApi;

use Symfony\Component\HttpFoundation\Response;
use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Url;
use WP_Error;

class Authentication extends Hooker
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
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 20;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    /**
     * @param $errors
     * @return WP_Error|null
     * @throws PathNotFoundException
     */
    public static function checkUserAuthorization($errors): ?WP_Error
    {
        if (self::isUnauthorized()) {
            return new WP_Error(Response::HTTP_UNAUTHORIZED, __('Unauthorized to access route.'));
        }

        return $errors;
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    private static function isUnauthorized(): bool
    {
        if (in_array(Url::getCurrentRestApiEndpoint(), Config::tryToGetOrDefault('rest-api.routes.public', []))) {
            return false;
        }

        if (is_user_logged_in()) {
            return false;
        }

        return true;
    }
}

<?php

namespace Wordless\Hookers\RestApi;

use Symfony\Component\HttpFoundation\Response;
use Wordless\Abstractions\Enums\RestApiRoutes;
use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\InvalidRoutePermission;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Str;
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
    protected const FUNCTION = 'restApiIsEnabled';
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
     * @throws InvalidRoutePermission
     * @throws PathNotFoundException
     */
    public static function restApiIsEnabled($errors): ?WP_Error
    {
        if (self::isUnauthorized()) {
            return new WP_Error(Response::HTTP_UNAUTHORIZED, __('Unauthorized to access route.'));
        }

        if (($error = self::checkForAuthRoutes()) instanceof WP_Error) {
            return $error;
        }

        return $errors;
    }

    /**
     * @return WP_Error|null
     * @throws InvalidRoutePermission
     * @throws PathNotFoundException
     */
    private static function checkForAuthRoutes(): ?WP_Error
    {
        foreach (Config::tryToGetOrDefault('rest-api.endpoints.routes', []) as $index => $value) {
            if (!is_string($index)) {
                continue;
            }

            if (Str::after(Url::currentUri(), rest_get_url_prefix()) !== $index) {
                continue;
            }

            if (!in_array($value, [RestApiRoutes::AUTH, RestApiRoutes::PUBLIC])) {
                throw new InvalidRoutePermission($index, $value);
            }

            if ($value === RestApiRoutes::AUTH && !is_user_logged_in()) {
                return new WP_Error(Response::HTTP_UNAUTHORIZED, __('Unauthorized to access route.'));
            }
        }

        return null;
    }

    private static function isUnauthorized(): bool
    {
        if (Config::tryToGetOrDefault('rest-api.enabled') === false && !is_user_logged_in()) {
            return true;
        }

        if (Url::isUserRestApiRoute() && !is_user_logged_in()) {
            return true;
        }

        return false;
    }
}

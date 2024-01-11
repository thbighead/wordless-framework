<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\RestApi;

use Symfony\Component\HttpFoundation\Response;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Url;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;
use WP_Error;

class Authentication extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'checkUserAuthorization';

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

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::rest_authentication_errors;
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
        $public_endpoints = Config::tryToGetOrDefault('wordpress.rest-api.routes.public', []);
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

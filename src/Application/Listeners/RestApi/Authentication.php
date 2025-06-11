<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\RestApi;

use Symfony\Component\HttpFoundation\Response;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Url;
use Wordless\Application\Listeners\RestApi\Authentication\Exceptions\FailedToRetrievePublicApiEndpoints;
use Wordless\Application\Providers\RestApiProvider;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;
use WP_Error;

class Authentication extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'checkUserAuthorization';

    /**
     * @param WP_Error|true|null $errors
     * @return WP_Error|true|null
     * @throws FailedToRetrievePublicApiEndpoints
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
     * @throws FailedToRetrievePublicApiEndpoints
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
     * @throws FailedToRetrievePublicApiEndpoints
     */
    private static function isCurrentApiEndpointPublic(): bool
    {
        try {
            $public_endpoints = Config::wordpress()->ofKey(RestApiProvider::CONFIG_KEY)
                ->ofKey(RestApiProvider::CONFIG_KEY_ROUTES)
                ->get(RestApiProvider::CONFIG_ROUTES_KEY_PUBLIC, []);
        } catch (EmptyConfigKey|FailedToLoadConfigFile $exception) {
            throw new FailedToRetrievePublicApiEndpoints($exception);
        }

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

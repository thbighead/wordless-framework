<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\RestApi;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\RestApi\DefineEndpoints\Exceptions\InvalidRestApiMultipleConfigKey;
use Wordless\Application\Providers\RestApiProvider;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class DefineEndpoints extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'setRestApiRoutes';

    /**
     * @param array $endpoints
     * @return array
     * @throws EmptyConfigKey
     * @throws InvalidRestApiMultipleConfigKey
     * @throws PathNotFoundException
     */
    public static function setRestApiRoutes(array $endpoints): array
    {
        $routes_configuration = Config::wordpress()->ofKey(RestApiProvider::CONFIG_KEY)
            ->get(RestApiProvider::CONFIG_KEY_ROUTES, []);

        if (isset($routes_configuration[RestApiProvider::CONFIG_ROUTES_KEY_ALLOW])
            && isset($routes_configuration[RestApiProvider::CONFIG_ROUTES_KEY_DISALLOW])) {
            throw new InvalidRestApiMultipleConfigKey;
        }

        if (isset($routes_configuration[RestApiProvider::CONFIG_ROUTES_KEY_ALLOW])) {
            return self::allowEndpoints($endpoints, $routes_configuration[RestApiProvider::CONFIG_ROUTES_KEY_ALLOW]);
        }

        if (isset($routes_configuration[RestApiProvider::CONFIG_ROUTES_KEY_DISALLOW])) {
            return self::disallowEndpoints(
                $endpoints,
                $routes_configuration[RestApiProvider::CONFIG_ROUTES_KEY_DISALLOW]
            );
        }

        return $endpoints;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::rest_endpoints;
    }

    private static function allowEndpoints(array $endpoints, array $config_routes): array
    {
        $final_routes = [];

        foreach ($config_routes as $index => $route) {
            if (is_string($index)) {
                $route = $index;
            }

            $final_routes[$route] = $endpoints[$route];
        }

        return $final_routes;
    }

    private static function disallowEndpoints(array $endpoints, array $config_routes): array
    {
        foreach ($config_routes as $index => $route) {
            if (is_string($index)) {
                $route = $index;
            }

            unset($endpoints[$route]);
        }

        return $endpoints;
    }
}

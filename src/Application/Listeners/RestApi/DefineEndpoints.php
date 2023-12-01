<?php

namespace Wordless\Application\Listeners\RestApi;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Wordpress\Hook\Enums\Filter;

class DefineEndpoints extends FilterListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'setRestApiRoutes';

    public static function priority(): int
    {
        return 20;
    }

    /**
     * @param array $endpoints
     * @return array
     * @throws InvalidRestApiMultipleConfigKey
     * @throws PathNotFoundException
     */
    public static function setRestApiRoutes(array $endpoints): array
    {
        $routes_configuration = Config::tryToGetOrDefault('rest-api.routes');

        if (isset($routes_configuration[RestApiPolicy::ALLOW])
            && isset($routes_configuration[RestApiPolicy::DISALLOW])) {
            throw new InvalidRestApiMultipleConfigKey();
        }

        if (isset($routes_configuration[RestApiPolicy::ALLOW])) {
            return self::allowEndpoints($endpoints, $routes_configuration[RestApiPolicy::ALLOW]);
        }

        if (isset($routes_configuration[RestApiPolicy::DISALLOW])) {
            return self::disallowEndpoints($endpoints, $routes_configuration[RestApiPolicy::DISALLOW]);
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

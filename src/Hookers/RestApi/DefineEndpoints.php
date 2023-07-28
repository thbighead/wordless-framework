<?php

namespace Wordless\Hookers\RestApi;

use Wordless\Abstractions\Enums\RestApiPolicy;
use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\InvalidRestApiMultipleConfigKey;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;

class DefineEndpoints extends Hooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'setRestApiRoutes';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_endpoints';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 20;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

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

<?php

namespace Wordless\Hookers\RestApi;

use Wordless\Abstractions\Enums\RestApiPolicy;
use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\InvalidRestApiPolicy;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Environment;

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
     * @throws InvalidConfigKey
     * @throws InvalidRestApiPolicy
     * @throws PathNotFoundException
     */
    public static function setRestApiRoutes(array $endpoints): array
    {
        if (Environment::get('APP_ENV') === Environment::LOCAL) {
            return $endpoints;
        }

        switch ($policy = Config::get('rest-api.endpoints.policy')) {
            case RestApiPolicy::ALLOW:
                return self::allowEndpoints($endpoints, Config::get('rest-api.endpoints.routes'));
            case RestApiPolicy::DISALLOW:
                return self::disallowEndpoints($endpoints, Config::get('rest-api.endpoints.routes'));
            default:
                throw new InvalidRestApiPolicy($policy);
        }
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

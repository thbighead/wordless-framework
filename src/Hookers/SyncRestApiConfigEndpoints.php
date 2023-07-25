<?php

namespace App\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;

class SyncRestApiConfigEndpoints extends Hooker
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
     * @throws PathNotFoundException
     */
    public static function setRestApiRoutes(array $endpoints): array
    {
        $rest_config = Config::tryToGetOrDefault('rest-api');

        if (isset($rest_config['enable']) && $rest_config['enable'] === false) {
            return [];
        }

        if (!isset($rest_config['endpoints']['policy']) || !isset($rest_config['endpoints']['routes'])) {
            throw new \Exception('Missing Rest API configuration file parameters.');
        }

        if ($rest_config['endpoints']['policy'] === 'allow') {
            $endpoints = self::allowEndpoints($endpoints, $rest_config['endpoints']['routes']);
        }

        if ($rest_config['endpoints']['policy'] === 'disallow') {
            $endpoints = self::disallowEndpoints($endpoints, $rest_config['endpoints']['routes']);
        }

        return $endpoints;
    }

    private static function allowEndpoints(array $endpoints, array $config_routes): array
    {
        $final_routes = [];

        foreach ($config_routes as $route => $parameter) {
            if (is_int($route)) {
                $route = $parameter;
            }

            $final_routes[$route] = $endpoints[$route];
        }

        return $final_routes;
    }

    private static function disallowEndpoints(array $endpoints, array $config_routes): array
    {
        foreach ($config_routes as $route => $parameters) {
            if (is_int($route)) {
                $route = $parameters;
            }

            unset($endpoints[$route]);
        }

        return $endpoints;
    }
}

<?php

namespace Wordless\Application\Cachers;

use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Wordpress\ApiController;

class ApiControllerCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'api_controllers.php';
    }

    /**
     * @return array
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     */
    protected function mountCacheArray(): array
    {
        $api_controllers_cache_array = [];

        foreach (ApiController::loadProvidedApiControllers() as $api_controller_namespace) {
            $api_controllers_cache_array[$api_controller_namespace] = $api_controller_namespace;
        }

        return $api_controllers_cache_array;
    }
}

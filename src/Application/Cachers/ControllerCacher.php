<?php

namespace Wordless\Application\Cachers;

use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Wordpress\ApiController;

class ControllerCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'controllers.php';
    }

    /**
     * @return array
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     */
    protected function mountCacheArray(): array
    {
        $controllers_cache_array = [];

        foreach (ApiController::loadProvidedApiControllers() as $controller_namespace) {
            $controllers_cache_array[$controller_namespace] = $controller_namespace;
        }

        return $controllers_cache_array;
    }
}

<?php

namespace Wordless\Abstractions\Cachers;

use ReflectionException;
use ReflectionMethod;
use Wordless\Adapters\WordlessController;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Hookers\BootControllers;

class ControllerCacher extends BaseCacher
{
    protected function cacheFilename(): string
    {
        return 'controllers.php';
    }

    /**
     * @throws ReflectionException
     * @throws PathNotFoundException
     */
    protected function mountCacheArray(): array
    {
        $controllers_cache_array = [];

        foreach (
            BootControllers::yieldBootableControllersPathAndResourceNameByReadingDirectory()
            as $controller_path_and_resource_name
        ) {
            require_once $controller_path_and_resource_name[0];
            /** @var WordlessController $controller */
            $controller = new $controller_path_and_resource_name[1];

            $controllers_cache_array[$controller_path_and_resource_name[1]] = [
                    'path' => $controller_path_and_resource_name[0],
                ] + $this->extractResourceNameAndVersionFromController($controller, $controller_path_and_resource_name[1]);
        }

        return $controllers_cache_array;
    }

    /**
     * @throws ReflectionException
     */
    private function extractResourceNameAndVersionFromController(
        WordlessController $controller,
        string             $controller_class_resource_name
    ): array
    {
        $controllerResourceNameMethod = new ReflectionMethod($controller_class_resource_name, 'resourceName');
        $controllerVersionMethod = new ReflectionMethod($controller_class_resource_name, 'version');
        $controllerResourceNameMethod->setAccessible(true);
        $controllerVersionMethod->setAccessible(true);

        return [
            'resource_name' => $controllerResourceNameMethod->invoke($controller),
            'version' => $controllerVersionMethod->invoke($controller),
        ];
    }
}
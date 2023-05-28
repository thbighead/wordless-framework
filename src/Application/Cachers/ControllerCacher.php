<?php

namespace Wordless\Application\Cachers;

use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Wordpress\ApiController;

class ControllerCacher extends Cacher
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
            ApiController::yieldBootableControllersPathAndResourceNameByReadingDirectory()
            as $controller_path_and_resource_name
        ) {
            require_once $controller_path_and_resource_name[0];

            /** @var ApiController $controller_namespaced_class */
            $controller_namespaced_class = $controller_path_and_resource_name[1];
            $controller = $controller_namespaced_class::getInstance();

            $controllers_cache_array[$controller_path_and_resource_name[1]] = [
                    'path' => $controller_path_and_resource_name[0],
                ] + $this->extractResourceNameAndVersionFromController(
                    $controller,
                    $controller_path_and_resource_name[1]
                );
        }

        return $controllers_cache_array;
    }

    /**
     * @param ApiController $controller
     * @param string $controller_class_resource_name
     * @return array
     * @throws ReflectionException
     */
    private function extractResourceNameAndVersionFromController(
        ApiController $controller,
        string        $controller_class_resource_name
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

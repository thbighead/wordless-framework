<?php

namespace Wordless\Abstractions\Cachers;

use ReflectionException;
use ReflectionMethod;
use Wordless\Adapters\WordlessController;
use Wordless\Bootables\BootControllers;
use Wordless\Exception\PathNotFoundException;

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
            BootControllers::yieldBootableControllersPathAndNamespaceByReadingDirectory()
            as $controller_path_and_namespace
        ) {
            require_once $controller_path_and_namespace[0];
            /** @var WordlessController $controller */
            $controller = new $controller_path_and_namespace[1];

            $controllers_cache_array[$controller_path_and_namespace[1]] = [
                    'path' => $controller_path_and_namespace[0],
                ] + $this->extractNamespaceAndVersionFromController($controller, $controller_path_and_namespace[1]);
        }

        return $controllers_cache_array;
    }

    /**
     * @throws ReflectionException
     */
    private function extractNamespaceAndVersionFromController(
        WordlessController $controller,
        string             $controller_class_namespace
    ): array
    {
        $controllerNamespaceMethod = new ReflectionMethod($controller_class_namespace, 'namespace');
        $controllerVersionMethod = new ReflectionMethod($controller_class_namespace, 'version');
        $controllerNamespaceMethod->setAccessible(true);
        $controllerVersionMethod->setAccessible(true);

        return [
            'namespace' => $controllerNamespaceMethod->invoke($controller),
            'version' => $controllerVersionMethod->invoke($controller),
        ];
    }
}
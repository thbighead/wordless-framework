<?php

namespace Wordless\Hookers;

use Generator;
use Wordless\Abstractions\AbstractHooker;
use Wordless\Abstractions\InternalCache;
use Wordless\Adapters\WordlessController;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\FailedToGetControllerPathFromCachedData;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\WordPressFailedToFindRole;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class BootControllers extends AbstractHooker
{
    /**
     * @throws PathNotFoundException
     * @throws WordPressFailedToFindRole
     */
    public static function register()
    {
        try {
            $cached_controllers_data = InternalCache::getValueOrFail('controllers');

            foreach ($cached_controllers_data as $controller_full_namespace => $controller_cached_data) {
                $controller_pathing = $controller_cached_data['path'] ?? false;

                if (!$controller_pathing) {
                    throw new FailedToGetControllerPathFromCachedData($controller_cached_data);
                }

                self::requireAndRegisterController($controller_cached_data['path'], $controller_full_namespace);
            }
        } catch (FailedToFindCachedKey|FailedToGetControllerPathFromCachedData $exception) {
            foreach (
                self::yieldBootableControllersPathAndResourceNameByReadingDirectory() as $controller_path_and_namespace
            ) {
                self::requireAndRegisterController(
                    $controller_path_and_namespace[0],
                    $controller_path_and_namespace[1]
                );
            }
        }
    }

    /**
     * @return Generator
     * @throws PathNotFoundException
     */
    public static function yieldBootableControllersPathAndResourceNameByReadingDirectory(): Generator
    {
        $controllers_directory_path = ProjectPath::controllers();

        foreach (DirectoryFiles::recursiveRead($controllers_directory_path) as $controller_path) {
            if (is_dir($controller_path)) {
                continue;
            }

            if (Str::endsWith($controller_path, 'Controller.php')) {
                $controller_relative_filepath_without_extension = trim(Str::after(
                    substr($controller_path, 0, -4), // Removes '.php'
                    $controllers_directory_path
                ), DIRECTORY_SEPARATOR);
                $controller_full_namespace = '\\App\\Controllers';

                foreach (explode(
                             DIRECTORY_SEPARATOR,
                             $controller_relative_filepath_without_extension
                         ) as $controller_pathing) {
                    $controller_full_namespace .= "\\$controller_pathing";
                }

                yield [$controller_path, $controller_full_namespace];
            }
        }
    }

    /**
     * @param string $controller_pathing
     * @param string $controller_full_namespace
     * @throws WordPressFailedToFindRole
     */
    private static function requireAndRegisterController(string $controller_pathing, string $controller_full_namespace)
    {
        require_once $controller_pathing;
        /** @var WordlessController $controller */
        $controller = new $controller_full_namespace;

        $controller->register_routes();
        $controller->registerCapabilitiesToRoles();
    }
}
<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Adapters\WordlessController;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\WordPressFailedToFindRole;

class BootControllers extends AbstractHooker
{
    /**
     * @return void
     * @throws PathNotFoundException
     * @throws WordPressFailedToFindRole
     */
    public static function register()
    {
        foreach (WordlessController::all() as $controller_path_and_namespace) {
            self::requireAndRegisterController(
                $controller_path_and_namespace[0],
                $controller_path_and_namespace[1]
            );
        }
    }

    /**
     * @param string $controller_pathing
     * @param string $controller_full_namespace
     * @throws WordPressFailedToFindRole
     */
    private static function requireAndRegisterController(string $controller_pathing, string $controller_full_namespace)
    {
        /** @var WordlessController $controller_full_namespace */
        require_once $controller_pathing;
        /** @var WordlessController $controller */
        $controller = $controller_full_namespace::getInstance();

        $controller->register_routes();
    }
}

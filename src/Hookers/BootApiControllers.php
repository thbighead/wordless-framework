<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Adapters\ApiController;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\WordPressFailedToFindRole;

class BootApiControllers extends Hooker
{
    /**
     * @return void
     * @throws PathNotFoundException
     * @throws WordPressFailedToFindRole
     */
    public static function register()
    {
        foreach (ApiController::all() as $controller_path_and_namespace) {
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
        /** @var ApiController $controller_full_namespace */
        require_once $controller_pathing;
        /** @var ApiController $controller */
        $controller = $controller_full_namespace::getInstance();

        $controller->register_routes();
    }
}
<?php

namespace Wordless\Application\Hookers;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\Hooker;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

class BootApiControllers extends Hooker
{
    /**
     * @return void
     * @throws PathNotFoundException
     * @throws FailedToFindRole
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
     * @throws FailedToFindRole
     */
    private static function requireAndRegisterController(
        string $controller_pathing,
        string $controller_full_namespace
    ): void
    {
        /** @var ApiController $controller_full_namespace */
        require_once $controller_pathing;
        /** @var ApiController $controller */
        $controller = $controller_full_namespace::getInstance();

        $controller->register_routes();
    }
}

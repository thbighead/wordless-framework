<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

class BootApiControllers extends Listener
{
    /**
     * @return void
     * @throws FailedToFindRole
     * @throws PathNotFoundException
     * @throws InvalidDirectory
     */
    public static function register(): void
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

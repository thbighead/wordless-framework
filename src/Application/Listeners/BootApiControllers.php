<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class BootApiControllers extends ActionListener
{
    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function register(): void
    {
        foreach (ApiController::all() as $api_controller_namespace) {
            $controller = $api_controller_namespace::getInstance();

            $controller->register_routes();
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::rest_api_init;
    }
}

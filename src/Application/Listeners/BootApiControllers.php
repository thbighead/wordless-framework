<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\Listener;

class BootApiControllers extends Listener
{
    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function register(): void
    {
        foreach (Bootstrapper::getInstance()->getProvidedApiControllers() as $api_controller_namespace) {
            $controller = $api_controller_namespace::getInstance();

            $controller->register_routes();
        }
    }
}

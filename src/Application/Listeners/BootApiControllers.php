<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class BootApiControllers extends ActionListener
{
    /**
     * @return void
     * @throws FailedToLoadBootstrapper
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

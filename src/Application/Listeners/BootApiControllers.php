<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class BootApiControllers extends ActionListener
{
    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
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

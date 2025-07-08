<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class RegisterWidgets extends ActionListener
{
    /**
     * @return void
     * @throws InvalidProviderClass
     * @throws FormatException
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     * @throws DotEnvNotSetException
     */
    public static function register(): void
    {
        foreach (Bootstrapper::getInstance()->getLoadedProviders() as $loadedProvider) {
            foreach ($loadedProvider->registerWidgets() as $widgetRegistrar) {
                $widgetRegistrar::register();
            }
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::widgets_init;
    }
}

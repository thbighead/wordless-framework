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

class RegisterShortcodes extends ActionListener
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
        foreach (Bootstrapper::getInstance()->getLoadedProviders() as $loadedProvider) {
            foreach ($loadedProvider->registerShortcodes() as $shortcodeRegistrar) {
                $shortcodeRegistrar::make()->register();
            }
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}

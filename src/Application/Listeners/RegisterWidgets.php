<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class RegisterWidgets extends ActionListener
{
    /**
     * @return void
     * @throws FailedToLoadBootstrapper
     */
    public static function register(): void
    {
        foreach (Bootstrapper::getInstance()->getLoadedProviders() as $loadedProvider) {
            foreach ($loadedProvider->registerSidebars() as $sidebarRegistrar) {
                $sidebarRegistrar::register();
            }

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

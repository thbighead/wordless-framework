<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Listeners\HideDiagnosticsFromUserRoles;
use Wordless\Application\Listeners\RemoveAdditionalCssFromAdmin;
use Wordless\Application\Listeners\WordlessVersionOnAdmin;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Listener;

class AdminConfigProvider extends Provider
{
    /**
     * @return string[]|Listener[]
     */
    public function registerListeners(): array
    {
        return [
            DoNotLoadWpAdminBarOutsidePanel::class,
            HideDiagnosticsFromUserRoles::class,
            RemoveAdditionalCssFromAdmin::class,
            WordlessVersionOnAdmin::class,
        ];
    }

    /**
     * @return string[]|Provider[]
     */
    public function registerProviders(): array
    {
        return [
            AdminBarEnvironmentFlagProvider::class,
            FrontPageProvider::class,
        ];
    }
}

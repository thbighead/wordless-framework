<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\CustomLoginUrlHooker;
use Wordless\Application\Listeners\CustomLoginUrl\LoadCustomLoginUrlHooker;
use Wordless\Application\Listeners\CustomLoginUrl\NetworkSiteUrlCustomLoginUrlHooker;
use Wordless\Application\Listeners\CustomLoginUrl\RedirectCustomLoginUrlHooker;
use Wordless\Application\Listeners\CustomLoginUrl\SiteUrlCustomLoginUrlHooker;
use Wordless\Application\Listeners\CustomLoginUrl\WelcomeEmailWithCustomLoginUrlHooker;
use Wordless\Application\Listeners\CustomLoginUrl\WpLoadedCustomLoginUrlHooker;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;

class LoginRedirectProvider extends Provider
{

    private const CONFIG_PREFIX = 'wordpress.admin.';

    /**
     * @return string[]|Listener[]
     * @throws PathNotFoundException
     */
    public function registerListeners(): array
    {
        $additional_hooks_configs = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL, false)) {
            $additional_hooks_configs[] = LoadCustomLoginUrlHooker::class;
            $additional_hooks_configs[] = WpLoadedCustomLoginUrlHooker::class;
            $additional_hooks_configs[] = SiteUrlCustomLoginUrlHooker::class;
            $additional_hooks_configs[] = NetworkSiteUrlCustomLoginUrlHooker::class;
            $additional_hooks_configs[] = RedirectCustomLoginUrlHooker::class;
            $additional_hooks_configs[] = WelcomeEmailWithCustomLoginUrlHooker::class;
        }

        return $additional_hooks_configs;
    }

    /**
     * @return RemoveHookDTO[]
     * @throws PathNotFoundException
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterActionListeners(): array
    {
        $hooks_to_remove = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL, false)) {
            $hooks_to_remove[] = RemoveHookDTO::make('template_redirect')
                ->setFunction('wp_redirect_admin_locations', 1000);
        }

        return $hooks_to_remove;
    }

    /**
     * @return RemoveHookDTO[]
     */
    public function unregisterFilterListeners(): array
    {
        return [];
    }
}

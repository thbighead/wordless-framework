<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomLoginUrl\LoadCustomLoginUrl;
use Wordless\Application\Listeners\CustomLoginUrl\NetworkSiteUrlCustomLoginUrl;
use Wordless\Application\Listeners\CustomLoginUrl\RedirectCustomLoginUrl;
use Wordless\Application\Listeners\CustomLoginUrl\SiteUrlCustomLoginUrl;
use Wordless\Application\Listeners\CustomLoginUrl\WpLoadedCustomLoginUrl;
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

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . 'wp_custom_login_url', false)) {
            $additional_hooks_configs[] = LoadCustomLoginUrl::class;
            $additional_hooks_configs[] = WpLoadedCustomLoginUrl::class;
            $additional_hooks_configs[] = SiteUrlCustomLoginUrl::class;
            $additional_hooks_configs[] = NetworkSiteUrlCustomLoginUrl::class;
            $additional_hooks_configs[] = RedirectCustomLoginUrl::class;
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

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . 'wp_custom_login_url', false)) {
            $hooks_to_remove[] = RemoveHookDTO::make('template_redirect')
                ->setFunction('wp_redirect_admin_locations', 1000);
        }

        return $hooks_to_remove;
    }
}

<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomAdminUrl\LoadCustomLoginUrl;
use Wordless\Application\Listeners\CustomAdminUrl\NetworkSiteUrlCustomLoginUrl;
use Wordless\Application\Listeners\CustomAdminUrl\RedirectCustomLoginUrl;
use Wordless\Application\Listeners\CustomAdminUrl\SiteUrlCustomLoginUrl;
use Wordless\Application\Listeners\CustomAdminUrl\WpLoadedCustomAdminUrl;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Action;

class AdminRedirectProvider extends Provider
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
            $additional_hooks_configs[] = WpLoadedCustomAdminUrl::class;
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
            $hooks_to_remove[] = RemoveHookDTO::make(Action::template_redirect->value)
                ->setFunction('wp_redirect_admin_locations', 1000);
        }

        return $hooks_to_remove;
    }
}

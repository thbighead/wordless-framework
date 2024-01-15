<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener as CustomAdminUrlListener;
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
        if (!$this->hasCustomAdminUrlConfigured()) {
            return [];
        }

        return [
            LoadCustomLoginUrl::class,
            WpLoadedCustomAdminUrl::class,
            SiteUrlCustomLoginUrl::class,
            NetworkSiteUrlCustomLoginUrl::class,
            RedirectCustomLoginUrl::class,
        ];
    }

    /**
     * @return RemoveHookDTO[]
     * @throws PathNotFoundException
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterActionListeners(): array
    {
        if (!$this->hasCustomAdminUrlConfigured()) {
            return [];
        }

        return [
            RemoveHookDTO::make(Action::template_redirect->value)
                ->setFunction('wp_redirect_admin_locations', 1000),
        ];
    }

    /**
     * @return bool
     * @throws PathNotFoundException
     */
    private function hasCustomAdminUrlConfigured(): bool
    {
        return empty((string)Config::tryToGetOrDefault(
            self::CONFIG_PREFIX . CustomAdminUrlListener::CUSTOM_ADMIN_URL_KEY
        ));
    }
}

<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener;
use Wordless\Application\Listeners\CustomAdminUrl\Contracts\BaseListener as CustomAdminUrlListener;
use Wordless\Application\Listeners\CustomAdminUrl\LoadCustomAdminUrl;
use Wordless\Application\Listeners\CustomAdminUrl\NetworkSiteUrlCustomAdminUrl;
use Wordless\Application\Listeners\CustomAdminUrl\RedirectCustomAdminUrl;
use Wordless\Application\Listeners\CustomAdminUrl\SiteUrlCustomAdminUrl;
use Wordless\Application\Listeners\CustomAdminUrl\WpLoadedCustomAdminUrl;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Action;

class AdminCustomUrlProvider extends Provider
{
    private const ADMIN_DEFAULT_URI = 'wp-core';

    /**
     * @param bool $wrapped
     * @return string
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function getCustomUri(bool $wrapped = true): string
    {
        $custom_admin_uri = (string)Config::wordpressAdmin(
            BaseListener::CONFIG_KEY_CUSTOM_ADMIN_URI,
            self::ADMIN_DEFAULT_URI
        );

        if (empty($custom_admin_uri)) {
            $custom_admin_uri = self::ADMIN_DEFAULT_URI;
        }

        return $wrapped ? Str::wrap($custom_admin_uri) : trim($custom_admin_uri, '/');
    }

    /**
     * @return string[]|Listener[]
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public function registerListeners(): array
    {
        if (!$this->hasCustomAdminUrlConfigured()) {
            return [];
        }

        return [
            LoadCustomAdminUrl::class,
            WpLoadedCustomAdminUrl::class,
            SiteUrlCustomAdminUrl::class,
            NetworkSiteUrlCustomAdminUrl::class,
            RedirectCustomAdminUrl::class,
        ];
    }

    /**
     * @return RemoveHookDTO[]
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterActionListeners(): array
    {
        if (!$this->hasCustomAdminUrlConfigured()) {
            return [];
        }

        return [
            RemoveHookDTO::make(Action::template_redirect)
                ->setFunction('wp_redirect_admin_locations', 1000),
        ];
    }

    /**
     * @return bool
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function hasCustomAdminUrlConfigured(): bool
    {
        return empty((string)Config::wordpressAdmin(CustomAdminUrlListener::CONFIG_KEY_CUSTOM_ADMIN_URI));
    }
}

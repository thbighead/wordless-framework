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
use Wordless\Core\Bootstrapper;

class LoginRedirect
{
    private const CONFIG_PREFIX = 'wordpress.admin.';
    private const HOOK_TO_REMOVE = 'template_redirect';
    private const REMOVAL_ACTION = 'wp_redirect_admin_locations';
    private const REMOVAL_ACTION_PRIORITY = 1000;

    /**
     * @return array
     * @throws PathNotFoundException
     */
    public static function addAdditionalHooks(): array
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
     * @return array
     * @throws PathNotFoundException
     */
    public static function removeLoginTemplateHook(): array
    {
        $hooks_to_remove = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL, false)) {
            $hooks_to_remove[self::HOOK_TO_REMOVE] = [
                Bootstrapper::LISTENERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::REMOVAL_ACTION,
                Bootstrapper::LISTENERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => self::REMOVAL_ACTION_PRIORITY,
            ];
        }

        return $hooks_to_remove;
    }
}

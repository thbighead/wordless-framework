<?php

namespace Wordless\Abstractions;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Hookers\CustomLoginUrl\CustomLoginUrlHooker;
use Wordless\Hookers\CustomLoginUrl\LoadCustomLoginUrlHooker;
use Wordless\Hookers\CustomLoginUrl\NetworkSiteUrlCustomLoginUrlHooker;
use Wordless\Hookers\CustomLoginUrl\RedirectCustomLoginUrlHooker;
use Wordless\Hookers\CustomLoginUrl\SiteUrlCustomLoginUrlHooker;
use Wordless\Hookers\CustomLoginUrl\WelcomeEmailWithCustomLoginUrlHooker;
use Wordless\Hookers\CustomLoginUrl\WpLoadedCustomLoginUrlHooker;

class LoginRedirect
{
    private const CONFIG_PREFIX = 'wordpress.admin.';
    private const HOOK_TO_REMOVE = 'template_redirect';
    private const REMOVAL_ACTION = 'wp_redirect_admin_locations';
    private const REMOVAL_ACTION_PRIORITY = 1000;

    /**
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
     * @throws PathNotFoundException
     */
    public static function removeLoginTemplateHook(): array
    {
        $hooks_to_remove = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL, false)) {
            $hooks_to_remove[self::HOOK_TO_REMOVE] = [
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::REMOVAL_ACTION,
                Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => self::REMOVAL_ACTION_PRIORITY,
            ];
        }

        return $hooks_to_remove;
    }
}

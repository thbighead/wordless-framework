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
    private const CONFIG_PREFIX = 'admin.';

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
}

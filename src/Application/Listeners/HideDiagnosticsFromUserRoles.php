<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hooker;
use WP_User;

class HideDiagnosticsFromUserRoles extends Hooker
{
    public const SHOW_DIAGNOSTICS_CONFIG_KEY = 'show_diagnostics_only_to';
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'hideDiagnosticsFromUserRoles';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_dashboard_setup';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function hideDiagnosticsFromUserRoles()
    {
        $currentUser = wp_get_current_user();

        if (!($currentUser instanceof WP_User)) {
            return;
        }

        $allowed_roles_to_see_diagnostics = Config::tryToGetOrDefault(
            'admin.' . self::SHOW_DIAGNOSTICS_CONFIG_KEY,
            []
        );

        if (empty($allowed_roles_to_see_diagnostics)) {
            return;
        }

        foreach ($currentUser->roles as $current_user_role_slug) {
            if ($allowed_roles_to_see_diagnostics[$current_user_role_slug] ?? false) {
                return;
            }
        }

        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    }
}

<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use WP_User;

class HideDiagnosticsFromUserRoles extends AbstractHooker
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

        $allowed_roles_to_see_diagnostics =
            (include ProjectPath::config('admin.php'))[self::SHOW_DIAGNOSTICS_CONFIG_KEY] ?? [];

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
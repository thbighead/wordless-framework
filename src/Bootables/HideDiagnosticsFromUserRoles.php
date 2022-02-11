<?php

namespace Wordless\Bootables;

use Wordless\Abstractions\AbstractBootable;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use WP_User;

class HideDiagnosticsFromUserRoles extends AbstractBootable
{
    public static function register()
    {
        add_action('wp_dashboard_setup', [self::class, 'hideDiagnosticsFromUserRoles']);
    }

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
            (include ProjectPath::config('admin.php'))['show_diagnostics_only_to'] ?? [];

        if (empty($allowed_roles_to_see_diagnostics)) {
            return;
        }

        foreach ($currentUser->roles as $current_user_role_slug) {
            if ($allowed_roles_to_see_diagnostics[$current_user_role_slug]) {
                return;
            }
        }

        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    }
}
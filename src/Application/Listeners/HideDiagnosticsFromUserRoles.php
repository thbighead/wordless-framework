<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_User;

class HideDiagnosticsFromUserRoles extends ActionListener
{
    public const SHOW_DIAGNOSTICS_CONFIG_KEY = 'show_diagnostics_only_to';
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'hideDiagnosticsFromUserRoles';

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function hideDiagnosticsFromUserRoles(): void
    {
        $currentUser = wp_get_current_user();

        if (!($currentUser instanceof WP_User)) {
            return;
        }

        $allowed_roles_to_see_diagnostics = Config::wordpressAdmin(
            self::SHOW_DIAGNOSTICS_CONFIG_KEY,
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

    protected static function hook(): ActionHook
    {
        return Action::wp_dashboard_setup;
    }
}

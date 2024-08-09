<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Environment;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_Admin_Bar;

class EnvironmentOnAdminBar extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'addEnvironmentFlagToAdminBarMenu';

    public static function addEnvironmentFlagToAdminBarMenu(WP_Admin_Bar $wpAdminBar): void
    {
        $environment_name = Environment::get('APP_ENV');

        $wpAdminBar->add_node([
            'id' => "wordless_{$environment_name}_environment_admin_bar_flag",
            'parent' => 'top-secondary',
            'title' => $environment_name,
        ]);
    }

    public static function priority(): int
    {
        return PHP_INT_MAX;
    }

    protected static function hook(): ActionHook
    {
        return Action::admin_bar_menu;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }
}

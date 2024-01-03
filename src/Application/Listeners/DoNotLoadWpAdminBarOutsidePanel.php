<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class DoNotLoadWpAdminBarOutsidePanel extends ActionListener
{
    public const SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY = 'show_wp_admin_bar_outside_panel';

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeAdminBarWhenNotInAdmin';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function removeAdminBarWhenNotInAdmin(): void
    {
        if (!Config::tryToGetOrDefault('wordpress.admin.' . static::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY, false)
            && !is_admin()) {
            show_admin_bar(false);
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::after_setup_theme;
    }
}

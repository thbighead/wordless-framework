<?php

namespace Wordless\Application\Hookers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Hooker;

class DoNotLoadWpAdminBarOutsidePanel extends Hooker
{
    public const SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY = 'show_wp_admin_bar_outside_panel';

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeAdminBarWhenNotInAdmin';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'after_setup_theme';

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function removeAdminBarWhenNotInAdmin()
    {
        if (!Config::tryToGetOrDefault('admin.' . static::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY, false)
            && !is_admin()) {
            show_admin_bar(false);
        }
    }
}

<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;

class DoNotLoadWpAdminBarOutsidePanel extends AbstractHooker
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

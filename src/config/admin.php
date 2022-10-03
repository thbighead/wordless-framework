<?php

use Wordless\Abstractions\Bootstrapper;
use Wordless\Abstractions\WpSpeedUp;
use Wordless\Helpers\Roles;
use Wordless\Hookers\HideDiagnosticsFromUserRoles;
use Wordless\Hookers\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Hookers\SwitchImagickByGd;

return [
    WpSpeedUp::REMOVE_WP_EMOJIS_CONFIG_KEY => false,
    WpSpeedUp::SPEED_UP_WP_CONFIG_KEY => true,
    DoNotLoadWpAdminBarOutsidePanel::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY => true,
    SwitchImagickByGd::IMAGE_LIBRARY_CONFIG_KEY => SwitchImagickByGd::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK,
    HideDiagnosticsFromUserRoles::SHOW_DIAGNOSTICS_CONFIG_KEY => [
        Roles::ADMIN => true,
        Roles::AUTHOR => false,
    ],
    Bootstrapper::MENUS_CONFIG_KEY => [],
];

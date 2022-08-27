<?php

use Wordless\Abstractions\Bootstrapper;
use Wordless\Helpers\Roles;
use Wordless\Hookers\HideDiagnosticsFromUserRoles;
use Wordless\Hookers\DoNotLoadWpAdminBarOutsidePanel;

return [
    DoNotLoadWpAdminBarOutsidePanel::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY => true,
    HideDiagnosticsFromUserRoles::SHOW_DIAGNOSTICS_CONFIG_KEY => [
        Roles::ADMIN => true,
        Roles::AUTHOR => false,
    ],
    Bootstrapper::MENUS_CONFIG_KEY => [],
];

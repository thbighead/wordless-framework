<?php

use Wordless\Application\Hookers\ChooseImageEditor;
use Wordless\Application\Hookers\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Hookers\HideDiagnosticsFromUserRoles;
use Wordless\Core\Bootstrapper;
use Wordless\Core\WpSpeedUp;
use Wordless\Enums\StartOfWeek;
use Wordless\Wordpress\Models\Role;

return [
    WpSpeedUp::REMOVE_WP_EMOJIS_CONFIG_KEY => false,
    WpSpeedUp::SPEED_UP_WP_CONFIG_KEY => true,
    DoNotLoadWpAdminBarOutsidePanel::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY => true,
    ChooseImageEditor::IMAGE_LIBRARY_CONFIG_KEY => ChooseImageEditor::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK,
    HideDiagnosticsFromUserRoles::SHOW_DIAGNOSTICS_CONFIG_KEY => [
        Role::ADMIN => true,
        Role::AUTHOR => false,
    ],
    Bootstrapper::MENUS_CONFIG_KEY => [],
    StartOfWeek::KEY => StartOfWeek::SUNDAY,
    'datetime' => [
        'timezone' => 'UTC+0',
        'date_format' => 'F j, Y',
        'time_format' => 'H:i',
    ]
];

<?php

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Listeners\ChooseImageEditor;
use Wordless\Application\Listeners\CustomLoginUrl\CustomLoginUrlHooker;
use Wordless\Application\Listeners\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Listeners\HideDiagnosticsFromUserRoles;
use Wordless\Application\Providers\WpSpeedUp;
use Wordless\Core\Bootstrapper;
use Wordless\Wordpress\Enums\StartOfWeek;
use Wordless\Wordpress\Models\Role;

return [
    'languages' => [],
    'theme' => 'wordless',
    'version' => 'latest',
    'permalink' => '/%postname%/',
    'admin' => [
        WpSpeedUp::REMOVE_WP_EMOJIS_CONFIG_KEY => false,
        WpSpeedUp::SPEED_UP_WP_CONFIG_KEY => true,
        DoNotLoadWpAdminBarOutsidePanel::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY => true,
        ChooseImageEditor::IMAGE_LIBRARY_CONFIG_KEY => ChooseImageEditor::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK,
        HideDiagnosticsFromUserRoles::SHOW_DIAGNOSTICS_CONFIG_KEY => [
            Role::ADMIN => true,
            Role::AUTHOR => false,
        ],
        Bootstrapper::MENUS_CONFIG_KEY => [],
        CustomLoginUrlHooker::WP_REDIRECT_URL => false,
        CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL => false,
        'enable_comments' => false,
        Bootstrapper::ERROR_REPORTING_KEY => Environment::isProduction()
            ? E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED
            : E_ALL,
        StartOfWeek::KEY => StartOfWeek::sunday->value,
        'datetime' => [
            'timezone' => 'UTC+0',
            'date_format' => 'F j, Y',
            'time_format' => 'H:i',
        ],
    ]
];

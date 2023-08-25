<?php

use Wordless\Abstractions\Bootstrapper;
use Wordless\Abstractions\Enums\Role;
use Wordless\Abstractions\WpSpeedUp;
use Wordless\Helpers\Environment;
use Wordless\Hookers\ChooseImageEditor;
use Wordless\Hookers\CustomLoginUrl\CustomLoginUrlHooker;
use Wordless\Hookers\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Hookers\HideDiagnosticsFromUserRoles;

return [
    WpSpeedUp::REMOVE_WP_EMOJIS_CONFIG_KEY => false,
    WpSpeedUp::SPEED_UP_WP_CONFIG_KEY => true,
    DoNotLoadWpAdminBarOutsidePanel::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY => true,
    ChooseImageEditor::IMAGE_LIBRARY_CONFIG_KEY => ChooseImageEditor::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK,
    HideDiagnosticsFromUserRoles::SHOW_DIAGNOSTICS_CONFIG_KEY => [
        Role::ADMINISTRATOR => true,
        Role::AUTHOR => false,
    ],
    Bootstrapper::MENUS_CONFIG_KEY => [],
    CustomLoginUrlHooker::WP_REDIRECT_URL => false,
    CustomLoginUrlHooker::WP_CUSTOM_LOGIN_URL => false,
    'enable_comments' => false,
    Bootstrapper::ERROR_REPORTING_KEY => Environment::isProduction()
        ? E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED
        : E_ALL,
];

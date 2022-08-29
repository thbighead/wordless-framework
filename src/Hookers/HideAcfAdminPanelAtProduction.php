<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Helpers\Environment;

class HideAcfAdminPanelAtProduction extends AbstractHooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'hideAcfAdminPanel';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'acf/settings/show_admin';

    public static function hideAcfAdminPanel(): bool
    {
        return WP_ENVIRONMENT_TYPE !== Environment::PRODUCTION;
    }
}

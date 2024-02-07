<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class DoNotLoadWpAdminBarOutsidePanel extends ActionListener
{
    final public const SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY = 'show_wp_admin_bar_outside_panel';

    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeAdminBarWhenNotInAdmin';

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function removeAdminBarWhenNotInAdmin(): void
    {
        if (!Config::wordpressAdmin(
                static::SHOW_WP_ADMIN_BAR_OUTSIDE_PANEL_CONFIG_KEY,
                false
            ) && !is_admin()) {
            show_admin_bar(false);
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::after_setup_theme;
    }
}

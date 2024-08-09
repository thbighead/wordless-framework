<?php

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_Admin_Bar;

class EnvironmentOnAdminBar extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'addEnvironmentFlagToAdminBarMenu';

    /**
     * @param WP_Admin_Bar $wpAdminBar
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    public static function addEnvironmentFlagToAdminBarMenu(WP_Admin_Bar $wpAdminBar): void
    {
        $environment_name = Environment::get('APP_ENV');

        $wpAdminBar->add_node([
            'id' => "wordless_{$environment_name}_environment_admin_bar_flag",
            'parent' => 'top-secondary',
            'title' => $environment_name,
            'meta' => [
                'html' => '<div>oi</div>',
            ],
        ]);
    }

    public static function priority(): int
    {
        return PHP_INT_MAX;
    }

    protected static function hook(): ActionHook
    {
        return Action::admin_bar_menu;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }
}

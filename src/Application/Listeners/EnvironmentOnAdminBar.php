<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Application\Listeners\EnvironmentOnAdminBar\Exceptions\FailedToAddEnvironmentFlagToAdminMenu;
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
     * @throws FailedToAddEnvironmentFlagToAdminMenu
     */
    public static function addEnvironmentFlagToAdminBarMenu(WP_Admin_Bar $wpAdminBar): void
    {
        try {
            $environment_name = Environment::get('APP_ENV');

            $wpAdminBar->add_node([
                'id' => "wordless_{$environment_name}_environment_admin_bar_flag",
                'parent' => 'top-secondary',
                'title' => Str::titleCase($environment_name),
            ]);
        } catch (CannotResolveEnvironmentGet|FailedToCreateInflector $exception) {
            throw new FailedToAddEnvironmentFlagToAdminMenu($exception);
        }
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

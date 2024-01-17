<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use WP_Customize_Manager;

class RemoveAdditionalCssFromAdmin extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeAdditionalCss';

    public static function priority(): int
    {
        return 15;
    }

    public static function removeAdditionalCss(WP_Customize_Manager $manager): void
    {
        $manager->remove_section('custom_css');
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): ActionHook
    {
        return Action::customize_register;
    }
}

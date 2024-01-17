<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class DisableCptComments extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeCommentsSupport';

    public static function priority(): int
    {
        return 1;
    }

    /**
     * @param string $post_type
     * @return void
     * @throws PathNotFoundException
     */
    public static function removeCommentsSupport(string $post_type): void
    {
        if (Config::tryToGetOrDefault('wordpress.admin.enable_comments', false) === true) {
            return;
        }

        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): ActionHook
    {
        return Action::registered_post_type;
    }
}

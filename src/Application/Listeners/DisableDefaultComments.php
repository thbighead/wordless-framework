<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class DisableDefaultComments extends ActionListener
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
     * @return void
     * @throws PathNotFoundException
     */
    public static function removeCommentsSupport(): void
    {
        if (Config::tryToGetOrDefault('wordpress.admin.enable_comments', false) === true) {
            return;
        }

        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}

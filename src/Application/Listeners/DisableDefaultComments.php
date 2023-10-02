<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Listener;

class DisableDefaultComments extends Listener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeCommentsSupport';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'init';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 1;

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
}

<?php

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Listener;

class DisableCptComments extends Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeCommentsSupport';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'registered_post_type';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 1;

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
}

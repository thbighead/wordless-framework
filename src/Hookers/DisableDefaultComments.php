<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;

class DisableDefaultComments extends Hooker
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
     * @throws PathNotFoundException
     */
    public static function removeCommentsSupport()
    {
        if (Config::tryToGetOrDefault('admin.enable_comments', false) === true) {
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

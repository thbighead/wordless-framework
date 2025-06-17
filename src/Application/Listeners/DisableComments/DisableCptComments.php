<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\DisableComments;

use Wordless\Application\Listeners\DisableComments\Contracts\DisableCommentsActionListener;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class DisableCptComments extends DisableCommentsActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeCommentsSupport';

    /**
     * @param string $post_type
     * @return void
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function removeCommentsSupport(string $post_type): void
    {
        if (self::areCommentsEnabled()) {
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

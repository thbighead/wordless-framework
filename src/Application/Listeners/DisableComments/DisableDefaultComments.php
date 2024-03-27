<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\DisableComments;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\DisableComments\Contracts\DisableCommentsActionListener;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class DisableDefaultComments extends DisableCommentsActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeCommentsSupport';

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function removeCommentsSupport(): void
    {
        if (self::areCommentsEnabled()) {
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

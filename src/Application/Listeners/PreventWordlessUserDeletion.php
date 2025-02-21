<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\Models\User\WordlessUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToDeleteWordlessUser;

class PreventWordlessUserDeletion extends ActionListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'preventWordlessUserDeletion';

    public static function priority(): int
    {
        return 0;
    }

    /**
     * @param int $user_id
     * @return void
     * @throws TryingToDeleteWordlessUser
     */
    public static function preventWordlessUserDeletion(int $user_id): void
    {
        if ($user_id === WordlessUser::find()?->id()) {
            throw new TryingToDeleteWordlessUser;
        }
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): ActionHook
    {
        return Action::delete_user;
    }
}

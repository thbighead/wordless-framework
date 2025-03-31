<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\WordlessUser\Traits;

use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;

trait TempUser
{
    final public const FIRST_EMAIL = 'admin@mail.com';
    final public const FIRST_PASSWORD = 'wordless_admin';

    /**
     * @return User
     * @throws FailedToCreateUser
     */
    final public static function createFirstAdminUser(): User
    {
        $user = User::create(self::FIRST_EMAIL, self::FIRST_PASSWORD);

        $user->remove_role(DefaultRole::subscriber->name);
        $user->add_role(DefaultRole::admin->value);

        return $user;
    }

    final public static function getTempUser(): User
    {
        return User::findByEmail(self::FIRST_EMAIL);
    }
}

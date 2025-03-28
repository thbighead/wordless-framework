<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits;

use Wordless\Wordpress\Models\Role\Enums\DefaultRole;

trait TempUser
{
    final public const FIRST_EMAIL = 'admin@mail.com';
    final public const FIRST_PASSWORD = 'wordless_admin';

    final public static function createFirstAdminUser(): static
    {
        $user = static::create(self::FIRST_EMAIL, self::FIRST_PASSWORD);

        $user->remove_role(DefaultRole::subscriber->name);
        $user->add_role(DefaultRole::admin->value);

        return $user;
    }

    final public static function getTempUser(): static
    {
        return static::findByEmail(self::FIRST_EMAIL);
    }
}

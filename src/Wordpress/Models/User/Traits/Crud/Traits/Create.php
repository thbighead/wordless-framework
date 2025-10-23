<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\StandardRole;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;
use WP_Error;

trait Create
{
    /**
     * @param string $email
     * @param string $password
     * @param string|null $username
     * @param Role|StandardRole|string|null $role
     * @return static
     * @throws FailedToCreateUser
     */
    public static function create(
        string                        $email,
        string                        $password,
        ?string                       $username = null,
        Role|StandardRole|string|null $role = null
    ): static
    {
        if (($new_user_id = wp_create_user(
                $username ??= Str::before($email, '@'),
                $password,
                $email
            )) instanceof WP_Error) {
            throw new FailedToCreateUser($email, $password, $username, $new_user_id);
        }

        /** @var User $newUser */
        $newUser = static::findById($new_user_id);

        if ($role !== null) {
            $newUser->set_role(match (true) {
                $role instanceof Role => $role->name,
                $role instanceof StandardRole => $role->value,
                default => $role,
            });
        }

        return $newUser;
    }
}

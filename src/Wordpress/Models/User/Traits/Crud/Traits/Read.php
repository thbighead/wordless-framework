<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use WP_Role;
use WP_User;
use WP_User_Query;

trait Read
{
    public static function findByEmail(string $user_email, bool $with_acfs = false): ?static
    {
        return self::findBy('email', $user_email, $with_acfs);
    }

    public static function findById(int $user_id, bool $with_acfs = false): ?static
    {
        return self::findBy('id', $user_id, $with_acfs);
    }

    public static function findBySlug(string $user_slug, bool $with_acfs = false): ?static
    {
        return self::findBy('slug', $user_slug, $with_acfs);
    }

    public static function findByUsername(string $username, bool $with_acfs = false): ?static
    {
        return self::findBy('login', $username, $with_acfs);
    }

    /**
     * @param DefaultRole|WP_Role|string $role
     * @param bool $with_acfs
     * @return static[]
     */
    public static function getByRole(DefaultRole|WP_Role|string $role, bool $with_acfs = true): array
    {
        $users = [];

        foreach ((new WP_User_Query(['role' => match (true) {
            $role instanceof DefaultRole => $role->value,
            $role instanceof WP_Role => $role->name,
            default => $role,
        }]))->get_results() as $wpUser) {
            if ($wpUser instanceof WP_User) {
                $users[$wpUser->ID] = new static($wpUser, $with_acfs);
            }
        }

        return $users;
    }

    private static function findBy(string $field, int|string $value, bool $with_acfs = false): ?static
    {
        return ($user = get_user_by($field, $value)) instanceof WP_User ? new static($user, $with_acfs) : null;
    }
}

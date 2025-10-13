<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Role\Enums\StandardRole;
use WP_Role;
use WP_User;
use WP_User_Query;

trait Read
{
    public static function findByEmail(string $user_email): ?static
    {
        return self::findBy('email', $user_email);
    }

    public static function findById(int $user_id): ?static
    {
        return self::findBy('id', $user_id);
    }

    public static function findBySlug(string $user_slug): ?static
    {
        return self::findBy('slug', $user_slug);
    }

    public static function findByUsername(string $username): ?static
    {
        return self::findBy('login', $username);
    }

    /**
     * @param StandardRole|WP_Role|string $role
     * @return static[]
     */
    public static function getByRole(StandardRole|WP_Role|string $role): array
    {
        $users = [];

        foreach ((new WP_User_Query(['role' => match (true) {
            $role instanceof StandardRole => $role->value,
            $role instanceof WP_Role => $role->name,
            default => $role,
        }]))->get_results() as $wpUser) {
            if ($wpUser instanceof WP_User) {
                $users[$wpUser->ID] = new static($wpUser);
            }
        }

        return $users;
    }

    private static function findBy(string $field, int|string $value): ?static
    {
        return ($user = get_user_by($field, $value)) instanceof WP_User ? new static($user) : null;
    }
}

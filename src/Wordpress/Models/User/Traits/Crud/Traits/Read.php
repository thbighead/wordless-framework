<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use WP_User;

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

    private static function findBy(string $field, int|string $value): ?static
    {
        return ($user = get_user_by($field, $value)) instanceof WP_User ? new static($user) : null;
    }
}

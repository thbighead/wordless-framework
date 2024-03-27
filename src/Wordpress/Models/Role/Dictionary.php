<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role;

use Wordless\Application\Libraries\DesignPattern\Singleton;
use WP_Role;

class Dictionary extends Singleton
{
    private static bool $loaded = false;
    /** @var array<string, WP_Role> $roles_keyed_by_name */
    private static array $roles_keyed_by_name;
    /** @var array<string, array<string, string|array<string, bool>>> $roles_as_array_keyed_by_name */
    private static array $roles_as_array_keyed_by_name;

    private static function init(): void
    {
        if (self::$loaded) {
            return;
        }

        self::$roles_keyed_by_name = wp_roles()->role_objects;
        self::$roles_as_array_keyed_by_name = wp_roles()->roles;

        self::$loaded = true;
    }

    public function all(): array
    {
        return self::$roles_keyed_by_name;
    }

    public function allAsArray(): array
    {
        return self::$roles_as_array_keyed_by_name;
    }

    /**
     * @return string[]
     */
    public function allNames(): array
    {
        return array_keys(self::$roles_keyed_by_name);
    }

    public function find(string $role): ?WP_Role
    {
        return self::$roles_keyed_by_name[$role] ?? null;
    }

    /**
     * @param string $role
     * @return array<string, string|array<string, bool>>|null
     */
    public function findAsArray(string $role): ?array
    {
        return self::$roles_as_array_keyed_by_name[$role] ?? null;
    }

    protected function __construct()
    {
        parent::__construct();

        self::init();
    }
}

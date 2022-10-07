<?php

namespace Wordless\Adapters;

use Wordless\Abstractions\RolesList;
use Wordless\Exceptions\FailedToCreateRole;
use Wordless\Helpers\Str;
use WP_Role;

class Role extends WP_Role
{
    public const ADMIN = 'administrator';
    public const AUTHOR = 'author';
    public const CONTRIBUTOR = 'contributor';
    public const EDITOR = 'editor';
    public const KEY = 'role';
    public const SUBSCRIBER = 'subscriber';
    private const DEFAULT = [
        self::ADMIN => self::ADMIN,
        self::AUTHOR => self::AUTHOR,
        self::CONTRIBUTOR => self::CONTRIBUTOR,
        self::EDITOR => self::EDITOR,
        self::SUBSCRIBER => self::SUBSCRIBER,
    ];

    private static RolesList $wpRolesRepository;
    private bool $is_default;

    /**
     * @param string|WP_Role $role
     * @param bool[] $capabilities
     */
    public function __construct($role, array $capabilities = [])
    {
        if ($role instanceof WP_Role) {
            $capabilities = $role->capabilities;
            $role = $role->name;
        }

        parent::__construct($role, $capabilities);
    }

    /**
     * @return Role[]
     */
    public static function all(): array
    {
        return self::getRepository()->getRoleObjects();
    }

    public static function allAsArray(): array
    {
        return self::getRepository()->roles;
    }

    /**
     * @return string[]
     */
    public static function allNames(): array
    {
        return self::getRepository()->get_names();
    }

    /**
     * @param string $name
     * @param bool[] $capabilities
     * @return Role
     * @throws FailedToCreateRole
     */
    public static function create(string $name, array $capabilities = []): Role
    {
        $newRole = self::getRepository()->add_role($slug_key = Str::slugCase($name), $name, $capabilities);

        if (!($newRole instanceof WP_Role)) {
            throw new FailedToCreateRole($slug_key, $name, $capabilities);
        }

        return new static($newRole);
    }

    public static function delete(string $role)
    {
        self::getRepository()->remove_role(Str::slugCase($role));
    }

    public static function find(string $role): ?Role
    {
        if ($roleObject = self::getRepository()->get_role(Str::slugCase($role))) {
            return new static($roleObject);
        }

        return null;
    }

    public static function isDefaultByName(string $role): bool
    {
        return (bool)(self::DEFAULT[Str::slugCase($role)] ?? false);
    }

    private static function getRepository(): RolesList
    {
        return self::$wpRolesRepository ?? self::$wpRolesRepository = new RolesList;
    }

    public function addCapability(string $capability)
    {
        $this->add_cap($capability);
    }

    public function can(string $capability): bool
    {
        return $this->has_cap($capability);
    }

    public function isDefault(): bool
    {
        return $this->is_default ?? $this->is_default = self::isDefaultByName($this->name);
    }

    public function removeCapability(string $capability)
    {
        $this->remove_cap($capability);
    }

    /**
     * @param bool[] $capabilities
     * @return void
     */
    public function syncCapabilities(array $capabilities)
    {
        foreach ($capabilities as $capability => $can) {
            $can ? $this->addCapability($capability) : $this->removeCapability($capability);
        }
    }
}

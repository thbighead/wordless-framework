<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use Wordless\Wordpress\RolesList;
use WP_Role;

class Role extends WP_Role
{
    final public const KEY = 'role';

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

    public function addCapability(string $capability): void
    {
        $this->add_cap($capability);
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

    public function can(string $capability): bool
    {
        return $this->has_cap($capability);
    }

    /**
     * @param string $name
     * @param bool[] $capabilities
     * @return Role
     * @throws FailedToCreateRole
     * @throws InvalidArgumentException
     */
    public static function create(string $name, array $capabilities = []): Role
    {
        $newRole = self::getRepository()->add_role($slug_key = Str::slugCase($name), $name, $capabilities);

        if (!($newRole instanceof WP_Role)) {
            throw new FailedToCreateRole($slug_key, $name, $capabilities);
        }

        return new static($newRole);
    }

    /**
     * @param string $role
     * @return void
     * @throws InvalidArgumentException
     */
    public static function delete(string $role): void
    {
        self::getRepository()->remove_role(Str::slugCase($role));
    }

    /**
     * @param string $role
     * @return Role|null
     * @throws FailedToFindRole
     * @throws InvalidArgumentException
     */
    public static function find(string $role): ?Role
    {
        if ($roleObject = self::getRepository()->get_role($role = Str::slugCase($role))) {
            return new static($roleObject);
        }

        throw new FailedToFindRole($role);
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isDefault(): bool
    {
        return $this->is_default ?? $this->is_default = self::isDefaultByName($this->name);
    }

    /**
     * @param string $role
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function isDefaultByName(string $role): bool
    {
        return DefaultRole::tryFrom(Str::slugCase($role)) !== null;
    }

    public function removeCapability(string $capability): void
    {
        $this->remove_cap($capability);
    }

    /**
     * @param bool[] $capabilities
     * @return void
     */
    public function syncCapabilities(array $capabilities): void
    {
        foreach ($capabilities as $capability => $can) {
            $can ? $this->addCapability($capability) : $this->removeCapability($capability);
        }
    }

    private static function getRepository(): RolesList
    {
        return self::$wpRolesRepository ?? self::$wpRolesRepository = new RolesList;
    }
}

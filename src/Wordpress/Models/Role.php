<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use InvalidArgumentException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Role\Dictionary;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use WP_Role;

class Role extends WP_Role
{
    final public const KEY = 'role';

    private static Dictionary $wpRolesRepository;
    private bool $is_default;

    /**
     * @return Role[]
     */
    public static function all(): array
    {
        return self::getDictionary()->getRoleObjects();
    }

    public static function allAsArray(): array
    {
        return self::getDictionary()->getRepository()->roles;
    }

    /**
     * @return string[]
     */
    public static function allNames(): array
    {
        return self::getDictionary()->getRepository()->get_names();
    }

    /**
     * @param string $name
     * @param string $capability
     * @param string ...$capabilities
     * @return Role
     * @throws FailedToCreateRole
     * @throws InvalidArgumentException
     */
    public static function create(string $name, string $capability, string ...$capabilities): Role
    {
        $capabilities = self::mountCapabilities($capability, ...$capabilities);
        $newRole = self::getDictionary()->getRepository()->add_role($slug_key = Str::slugCase($name), $name, $capabilities);

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
        self::getDictionary()->getRepository()->remove_role(Str::slugCase($role));
    }

    /**
     * @param string $role
     * @return Role|null
     * @throws FailedToFindRole
     * @throws InvalidArgumentException
     */
    public static function find(string $role): ?Role
    {
        if ($roleObject = self::getDictionary()->getRepository()->get_role($role = Str::slugCase($role))) {
            return new static($roleObject);
        }

        throw new FailedToFindRole($role);
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

    private static function getDictionary(): Dictionary
    {
        return self::$wpRolesRepository ?? self::$wpRolesRepository = new Dictionary;
    }

    /**
     * @param string $capability
     * @param string ...$capabilities
     * @return array<string, true>
     */
    private static function mountCapabilities(string $capability, string ...$capabilities): array
    {
        $mounted_capabilities = [];

        foreach (Arr::prepend($capabilities, $capability) as $capability) {
            $mounted_capabilities[$capability] = true;
        }

        return $mounted_capabilities;
    }

    /**
     * @param WP_Role|string $role
     * @throws FailedToFindRole
     * @throws InvalidArgumentException
     */
    public function __construct(WP_Role|string $role)
    {
        if (!($role instanceof WP_Role)) {
            $role = static::find($role);
        }

        parent::__construct($role->name, $role->capabilities);
    }

    public function addCapability(string $capability): void
    {
        $this->add_cap($capability);
    }

    public function can(string $capability): bool
    {
        return $this->has_cap($capability);
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isDefault(): bool
    {
        return $this->is_default ?? $this->is_default = self::isDefaultByName($this->name);
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
}

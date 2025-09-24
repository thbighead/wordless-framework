<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use Wordless\Wordpress\Models\Role\Traits\Repository;
use WP_Role;

class Role extends WP_Role
{
    use Repository;

    final public const KEY = 'role';

    private bool $is_default;

    public static function isDefaultByName(string $role): bool
    {
        return DefaultRole::tryFrom(Str::slugCase($role)) !== null;
    }

    /**
     * @param WP_Role|string $role
     * @throws FailedToFindRole
     * @throws InvalidArgumentException
     */
    public function __construct(WP_Role|string $role)
    {
        if (!($role instanceof WP_Role)) {
            $role = static::findOrFail($role);
        }

        parent::__construct($role->name, $role->capabilities);
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
}

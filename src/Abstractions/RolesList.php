<?php

namespace Wordless\Abstractions;

use Wordless\Adapters\PostType;
use Wordless\Adapters\Role;
use Wordless\Exceptions\FailedToCreateRole;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Str;
use WP_Roles;

class RolesList extends WP_Roles
{
    /** @var Role[] $roleObjects */
    private array $roleObjects = [];

    public function __construct($site_id = null)
    {
        parent::__construct($site_id);
    }

    /**
     * @return void
     * @throws FailedToCreateRole
     * @throws PathNotFoundException
     */
    public static function sync()
    {
        $adminRole = Role::find(Role::ADMIN);

        foreach (PostType::getAllCustom() as $customPostType) {
            $adminRole->syncCapabilities(array_combine(
                $permissions = array_values($customPostType->getPermissions()),
                array_fill(0, count($permissions), true)
            ));
        }

        foreach (Config::tryToGetOrDefault('permissions', []) as $role_key => $permissions) {
            $role = Role::find($role_key);

            if ($role === null) {
                Role::create(
                    Str::titleCase(Str::replace($role_key, ['-', '_'], ' ')),
                    array_filter($permissions, function (bool $value) {
                        return $value;
                    })
                );
                continue;
            }

            $role->syncCapabilities($permissions);
        }
    }

    /**
     * @return Role[]
     */
    public function getRoleObjects(): array
    {
        if ($this->shouldUpdateList()) {
            foreach ($this->role_objects as $role_key => $roleObject) {
                $this->roleObjects[$role_key] = $roleObject;
            }
        }

        return $this->roleObjects;
    }

    private function shouldUpdateList(): bool
    {
        foreach ($this->role_objects as $role_key => $roleObject) {
            if (!($this->roleObjects[$role_key] ?? false)) {
                return true;
            }
        }

        return false;
    }
}

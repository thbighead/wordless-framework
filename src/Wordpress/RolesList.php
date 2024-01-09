<?php

namespace Wordless\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
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

    /**
     * @return void
     * @throws FailedToCreateRole
     * @throws PathNotFoundException
     * @throws FailedToFindRole
     */
    public static function sync(): void
    {
        self::syncPermissionsToAdminAsDefault();
        self::syncConfiguredPermissions();
    }

    /**
     * @return void
     * @throws FailedToCreateRole
     * @throws PathNotFoundException
     * @throws InvalidArgumentException
     */
    public static function syncConfiguredPermissions(): void
    {
        foreach (Config::tryToGetOrDefault('wordpress.permissions', []) as $role_key => $permissions) {
            try {
                $role = Role::find($role_key);
            } catch (FailedToFindRole) {
                Role::create(
                    Str::titleCase($role_key),
                    array_filter($permissions, function (bool $value) {
                        return $value;
                    })
                );
                continue;
            }

            $role->syncCapabilities($permissions);
        }
    }

    public static function syncCustomPostTypesPermissionsToRole(Role $role): void
    {
        foreach (PostType::getAllCustom() as $customPostType) {
            $role->syncCapabilities(array_combine(
                $permissions = array_values($customPostType->getPermissions()),
                array_fill(0, count($permissions), true)
            ));
        }
    }

    public static function syncCustomTaxonomiesPermissionsToRole(Role $role): void
    {
        foreach (Taxonomy::getAllCustom() as $customPostType) {
            $role->syncCapabilities(array_combine(
                $permissions = array_values($customPostType->getPermissions()),
                array_fill(0, count($permissions), true)
            ));
        }
    }

    /**
     * @return void
     * @throws PathNotFoundException
     * @throws FailedToFindRole
     */
    public static function syncPermissionsToAdminAsDefault(): void
    {
        self::syncCustomPostTypesPermissionsToRole($adminRole = Role::find(DefaultRole::admin->value));
        self::syncRestResourcesPermissionsToRole($adminRole);
    }

    /**
     * @param Role $role
     * @return void
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     * @throws InvalidProviderClass
     */
    public static function syncRestResourcesPermissionsToRole(Role $role): void
    {
        foreach (ApiController::all() as $controller_path_and_namespace) {
            self::requireAndRegisterControllersPermissions(
                $controller_path_and_namespace[0],
                $controller_path_and_namespace[1],
                $role
            );
        }
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

    /**
     * @param string $controller_pathing
     * @param string $controller_full_namespace
     * @param Role $role
     * @return void
     */
    private static function requireAndRegisterControllersPermissions(
        string $controller_pathing,
        string $controller_full_namespace,
        Role   $role
    ): void
    {
        /** @var ApiController $controller_full_namespace */
        require_once $controller_pathing;
        /** @var ApiController $controller */
        $controller = $controller_full_namespace::getInstance();

        $controller->registerCapabilitiesToRole($role);
    }
}

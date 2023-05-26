<?php

namespace Wordless\Wordpress;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Exceptions\WordPressFailedToFindRole;
use Wordless\Infrastructure\ApiController;
use Wordless\Wordpress\Models\CustomTaxonomyTerm;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
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
     * @throws WordPressFailedToFindRole
     */
    public static function sync()
    {
        self::syncPermissionsToAdminAsDefault();
        self::syncConfiguredPermissions();
    }

    /**
     * @return void
     * @throws FailedToCreateRole
     * @throws PathNotFoundException
     */
    public static function syncConfiguredPermissions()
    {
        foreach (Config::tryToGetOrDefault('permissions', []) as $role_key => $permissions) {
            try {
                $role = Role::find($role_key);
            } catch (WordPressFailedToFindRole $exception) {
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

    public static function syncCustomPostTypesPermissionsToRole(Role $role)
    {
        foreach (PostType::getAllCustom() as $customPostType) {
            $role->syncCapabilities(array_combine(
                $permissions = array_values($customPostType->getPermissions()),
                array_fill(0, count($permissions), true)
            ));
        }
    }

    public static function syncCustomTaxonomiesPermissionsToRole(Role $role)
    {
        foreach (CustomTaxonomyTerm::getAllCustom() as $customPostType) {
            $role->syncCapabilities(array_combine(
                $permissions = array_values($customPostType->getPermissions()),
                array_fill(0, count($permissions), true)
            ));
        }
    }

    /**
     * @return void
     * @throws PathNotFoundException
     * @throws WordPressFailedToFindRole
     */
    public static function syncPermissionsToAdminAsDefault()
    {
        self::syncCustomPostTypesPermissionsToRole($adminRole = Role::find(Role::ADMIN));
        self::syncRestResourcesPermissionsToRole($adminRole);
    }

    /**
     * @param Role $role
     * @return void
     * @throws PathNotFoundException
     * @throws WordPressFailedToFindRole
     */
    public static function syncRestResourcesPermissionsToRole(Role $role)
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
     * @throws WordPressFailedToFindRole
     */
    private static function requireAndRegisterControllersPermissions(string $controller_pathing, string $controller_full_namespace, Role $role)
    {
        /** @var ApiController $controller_full_namespace */
        require_once $controller_pathing;
        /** @var ApiController $controller */
        $controller = $controller_full_namespace::getInstance();

        $controller->registerCapabilitiesToRole($role);
    }
}

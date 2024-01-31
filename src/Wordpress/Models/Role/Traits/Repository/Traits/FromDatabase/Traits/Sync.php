<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

trait Sync
{
    /**
     * @return void
     * @throws FailedToCreateRole
     * @throws FailedToFindRole
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
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
        foreach (Config::get('wordpress.permissions', []) as $role_key => $permissions) {
            try {
                $role = Role::findOrFail($role_key);
            } catch (FailedToFindRole) {
                Role::create(
                    Str::titleCase($role_key),
                    ...array_keys(array_filter($permissions, function (bool $value): bool {
                        return $value;
                    }))
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

    /**
     * @param Role $role
     * @return void
     */
    public static function syncCustomTaxonomiesPermissionsToRole(Role $role): void
    {
        foreach (TaxonomyQueryBuilder::getInstance()->onlyCustom()->get() as $customTaxonomy) {
            $role->syncCapabilities(array_combine(
                $permissions = array_values((array)$customTaxonomy->cap),
                array_fill(0, count($permissions), true)
            ));
        }
    }

    /**
     * @return void
     * @throws FailedToFindRole
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws InvalidArgumentException
     */
    public static function syncPermissionsToAdminAsDefault(): void
    {
        self::syncCustomTaxonomiesPermissionsToRole($adminRole = Role::findOrFail(DefaultRole::admin->value));
        self::syncCustomPostTypesPermissionsToRole($adminRole);
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
        foreach (ApiController::all() as $controller_class_namespace) {
            self::registerControllersPermissions($controller_class_namespace, $role);
        }
    }

    /**
     * @param string|ApiController $controller_full_namespace
     * @param Role $role
     * @return void
     */
    private static function registerControllersPermissions(
        string|ApiController $controller_full_namespace,
        Role                 $role
    ): void
    {
        $controller_full_namespace::getInstance()->registerCapabilitiesToRole($role);
    }
}

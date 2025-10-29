<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase\Traits;

use Wordless\Application\Commands\SyncRoles;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\StandardRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase\Traits\Sync\Exceptions\SynchroniseFailed;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

trait Sync
{
    /**
     * @return void
     * @throws SynchroniseFailed
     */
    public static function sync(): void
    {
        try {
            self::syncPermissionsToAdminAsDefault();
            self::syncConfiguredPermissions();
        } catch (FailedToCreateInflector|FailedToCreateRole|FailedToFindRole|FailedToLoadBootstrapper $exception) {
            throw new SynchroniseFailed($exception);
        }
    }

    /**
     * @return void
     * @throws FailedToCreateInflector
     * @throws FailedToCreateRole
     */
    public static function syncConfiguredPermissions(): void
    {
        foreach (Config::wordpress(SyncRoles::CONFIG_KEY_PERMISSIONS, []) as $role_key => $permissions) {
            try {
                $role = Role::getOrFail($role_key);
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
        foreach (TaxonomyQueryBuilder::make()->onlyCustom()->get() as $customTaxonomy) {
            $role->syncCapabilities(array_combine(
                $permissions = array_values((array)$customTaxonomy->cap),
                array_fill(0, count($permissions), true)
            ));
        }
    }

    /**
     * @return void
     * @throws FailedToFindRole
     * @throws FailedToLoadBootstrapper
     */
    public static function syncPermissionsToAdminAsDefault(): void
    {
        self::syncCustomTaxonomiesPermissionsToRole($adminRole = Role::getOrFail(StandardRole::admin->value));
        self::syncCustomPostTypesPermissionsToRole($adminRole);
        self::syncRestResourcesPermissionsToRole($adminRole);
    }

    /**
     * @param Role $role
     * @return void
     * @throws FailedToLoadBootstrapper
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

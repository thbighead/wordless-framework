<?php

namespace Wordless\Contracts\Controller;

use Wordless\Adapters\Role;
use Wordless\Helpers\Arr;
use WP_Error;
use WP_REST_Request;

trait AuthorizationCheck
{
    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function create_item_permissions_check($request)
    {
        return $this->resolvePermission($this->createPermissionName());
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
        return $this->resolvePermission($this->deletePermissionName());
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function get_item_permissions_check($request)
    {
        return $this->resolvePermission($this->getItemPermissionName());
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function get_items_permissions_check($request)
    {
        return $this->resolvePermission($this->getItemsPermissionName());
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function update_item_permissions_check($request)
    {
        return $this->resolvePermission($this->updatePermissionName());
    }

    /**
     * @param Role|Role[] $roles
     * @return void
     */
    final public function registerCapabilitiesToRole($roles)
    {
        if (static::HAS_PERMISSIONS) {
            $this->syncPermissionsTo($roles);
        }
    }

    /**
     * @param Role|Role[] $roles
     * @return void
     */
    protected function syncPermissionsTo($roles)
    {
        foreach (Arr::wrap($roles) as $role) {
            $role->syncCapabilities([
                $this->deletePermissionName() => true,
                $this->getItemsPermissionName() => true,
                $this->getItemPermissionName() => true,
                $this->createPermissionName() => true,
                $this->updatePermissionName() => true,
            ]);
        }
    }

    private function buildForbiddenContextError(string $missing_capability): WP_Error
    {
        return new WP_Error(
            self::FORBIDDEN_CONTEXT_CODE,
            __('Sorry, you are not allowed to edit posts in this post type.')
            . sprintf(__(' Missing capability \'%s\'.'), $missing_capability),
            ['status' => rest_authorization_required_code()]
        );
    }

    private function createPermissionName(): string
    {
        return "store_{$this->resourceName()}";
    }

    private function deletePermissionName(): string
    {
        return "destroy_{$this->resourceName()}";
    }

    private function getItemPermissionName(): string
    {
        return "show_{$this->resourceName()}";
    }

    private function getItemsPermissionName(): string
    {
        return "index_{$this->resourceName()}";
    }

    /**
     * @param string $capability
     * @return bool|WP_Error
     */
    private function resolvePermission(string $capability)
    {
        if ($this->getAuthenticatedUser() === null || !$this->getAuthenticatedUser()->can($capability)) {
            return $this->buildForbiddenContextError($capability);
        }

        return true;
    }

    private function updatePermissionName(): string
    {
        return "update_{$this->resourceName()}";
    }
}

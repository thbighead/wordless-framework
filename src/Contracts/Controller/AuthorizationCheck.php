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
        return $this->resolvePermission(
            $this->createPermissionName(),
            static::METHOD_NAME_TO_REST_STORE_ITEM
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
        return $this->resolvePermission(
            $this->deletePermissionName(),
            static::METHOD_NAME_TO_REST_DESTROY_ITEM
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function get_item_permissions_check($request)
    {
        return $this->resolvePermission(
            $this->getItemPermissionName(),
            static::METHOD_NAME_TO_REST_SHOW_ITEM
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function get_items_permissions_check($request)
    {
        return $this->resolvePermission(
            $this->getItemsPermissionName(),
            static::METHOD_NAME_TO_REST_INDEX_ITEMS
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function update_item_permissions_check($request)
    {
        return $this->resolvePermission(
            $this->updatePermissionName(),
            static::METHOD_NAME_TO_REST_UPDATE_ITEM
        );
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

    private function buildForbiddenContextError(?string $missing_capability = null): WP_Error
    {
        $message = __('Sorry, you are not allowed to edit posts in this post type.');

        if (!empty($missing_capability)) {
            $message .= sprintf(__(' Missing capability \'%s\'.'), $missing_capability);
        }

        return new WP_Error(
            self::FORBIDDEN_CONTEXT_CODE,
            $message,
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

    private function isRouteMethodPublic(string $route_method): bool
    {
        return static::PUBLIC_METHOD_ROUTES[$route_method] ?? false;
    }

    /**
     * @param string $capability
     * @param string $route_method
     * @return bool|WP_Error
     */
    private function resolvePermission(string $capability, string $route_method)
    {
        if (!$this->isRouteMethodPublic($route_method) && $this->getAuthenticatedUser() === null) {
            return $this->buildForbiddenContextError();
        }

        if (static::HAS_PERMISSIONS && !$this->getAuthenticatedUser()->can($capability)) {
            return $this->buildForbiddenContextError($capability);
        }

        return true;
    }

    private function updatePermissionName(): string
    {
        return "update_{$this->resourceName()}";
    }
}

<?php

namespace Wordless\Infrastructure\Wordpress\ApiController\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Role;
use WP_Error;
use WP_REST_Request;

trait AuthorizationCheck
{
    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    final public function create_item_permissions_check($request)
    {
        return $this->storeAuthorizationCheck();
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    final public function delete_item_permissions_check($request)
    {
        return $this->destroyAuthorizationCheck();
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    final public function get_item_permissions_check($request)
    {
        return $this->showAuthorizationCheck();
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    final public function get_items_permissions_check($request)
    {
        return $this->indexAuthorizationCheck();
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    final public function update_item_permissions_check($request)
    {
        return $this->updateAuthorizationCheck();
    }

    /**
     * @param Role|Role[] $roles
     * @return void
     */
    final public function registerCapabilitiesToRole(Role|array $roles): void
    {
        if (static::HAS_PERMISSIONS) {
            $this->syncPermissionsTo($roles);
        }
    }

    protected function buildForbiddenContextError(?string $missing_capability = null): WP_Error
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

    /**
     * @return bool|WP_Error
     */
    protected function destroyAuthorizationCheck()
    {
        return $this->resolvePermission(
            $this->deletePermissionName(),
            self::METHOD_NAME_TO_REST_DESTROY_ITEM
        );
    }

    /**
     * @return bool|WP_Error
     */
    protected function indexAuthorizationCheck()
    {
        return $this->resolvePermission(
            $this->getItemsPermissionName(),
            self::METHOD_NAME_TO_REST_INDEX_ITEMS
        );
    }

    /**
     * @return bool|WP_Error
     */
    protected function showAuthorizationCheck()
    {
        return $this->resolvePermission(
            $this->getItemPermissionName(),
            self::METHOD_NAME_TO_REST_SHOW_ITEM
        );
    }

    /**
     * @return bool|WP_Error
     */
    protected function storeAuthorizationCheck()
    {
        return $this->resolvePermission(
            $this->createPermissionName(),
            self::METHOD_NAME_TO_REST_STORE_ITEM
        );
    }

    /**
     * @param Role|Role[] $roles
     * @return void
     */
    protected function syncPermissionsTo(Role|array $roles): void
    {
        foreach (Arr::wrap($roles) as $role) {
            /** @var Role $role */
            $role->syncCapabilities([
                $this->deletePermissionName() => true,
                $this->getItemsPermissionName() => true,
                $this->getItemPermissionName() => true,
                $this->createPermissionName() => true,
                $this->updatePermissionName() => true,
            ]);
        }
    }

    /**
     * @return bool|WP_Error
     */
    protected function updateAuthorizationCheck()
    {
        return $this->resolvePermission(
            $this->updatePermissionName(),
            self::METHOD_NAME_TO_REST_UPDATE_ITEM
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

    private function isRouteMethodProtectedByAuthentication(string $route_method): bool
    {
        return static::AUTHENTICATION_PROTECTED_METHOD_ROUTES[$route_method] ?? false;
    }

    /**
     * @param string $capability
     * @param string $route_method
     * @return bool|WP_Error
     */
    private function resolvePermission(string $capability, string $route_method)
    {
        if ($this->isRouteMethodProtectedByAuthentication($route_method) && $this->getAuthenticatedUser() === null) {
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

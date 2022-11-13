<?php

namespace Wordless\Contracts\Controller;

use Wordless\Adapters\AbstractValidatedRequest;
use Wordless\Adapters\Response;
use Wordless\Adapters\Role;
use WP_Error;
use WP_REST_Request;

trait PermissionsChecks
{
    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function create_item_permissions_check($request)
    {
        if (($permissionResult = $this->resolvePermission($this->createPermissionName())) instanceof WP_Error) {
            return $permissionResult;
        }

        if (!($request instanceof AbstractValidatedRequest)) {
            return $permissionResult;
        }

        if (!$request->validate()) {
            return $this->resolveInvalidRequest($request);
        }

        return $permissionResult;
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
        if (($permissionResult = $this->resolvePermission($this->deletePermissionName())) instanceof WP_Error) {
            return $permissionResult;
        }

        if (!($request instanceof AbstractValidatedRequest)) {
            return $permissionResult;
        }

        if (!$request->validate()) {
            return $this->resolveInvalidRequest($request);
        }

        return $permissionResult;
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function get_item_permissions_check($request)
    {
        if (($permissionResult = $this->resolvePermission($this->getItemPermissionName())) instanceof WP_Error) {
            return $permissionResult;
        }

        if (!($request instanceof AbstractValidatedRequest)) {
            return $permissionResult;
        }

        if (!$request->validate()) {
            return $this->resolveInvalidRequest($request);
        }

        return $permissionResult;
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function get_items_permissions_check($request)
    {
        if (($permissionResult = $this->resolvePermission($this->getItemsPermissionName())) instanceof WP_Error) {
            return $permissionResult;
        }

        if (!($request instanceof AbstractValidatedRequest)) {
            return $permissionResult;
        }

        if (!$request->validate()) {
            return $this->resolveInvalidRequest($request);
        }

        return $permissionResult;
    }

    /**
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public function update_item_permissions_check($request)
    {
        if (($permissionResult = $this->resolvePermission($this->updatePermissionName())) instanceof WP_Error) {
            return $permissionResult;
        }

        if (!($request instanceof AbstractValidatedRequest)) {
            return $permissionResult;
        }

        if (!$request->validate()) {
            return $this->resolveInvalidRequest($request);
        }

        return $permissionResult;
    }

    public function registerCapabilitiesToRole(Role $role)
    {
        $role->syncCapabilities([
            $this->deletePermissionName() => true,
            $this->getItemsPermissionName() => true,
            $this->getItemPermissionName() => true,
            $this->createPermissionName() => true,
            $this->updatePermissionName() => true,
        ]);
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
     * @param AbstractValidatedRequest $request
     * @return WP_Error
     */
    private function resolveInvalidRequest(AbstractValidatedRequest $request): WP_Error
    {
        return new WP_Error(
            Response::HTTP_422_UNPROCESSABLE_ENTITY,
            __('The requested data has validation errors.'),
            $request->getValidatedAsInvalidFields() // TODO recuperar mensagens de erro da ConstraintViolationList
        );
    }

    /**
     * @param string $capability
     * @return bool|WP_Error
     */
    private function resolvePermission(string $capability)
    {
        if (!$this->getAuthenticatedUser()->can($capability)) {
            return $this->buildForbiddenContextError($capability);
        }

        return true;
    }

    private function updatePermissionName(): string
    {
        return "update_{$this->resourceName()}";
    }
}

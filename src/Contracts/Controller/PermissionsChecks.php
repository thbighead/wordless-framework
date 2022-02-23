<?php

namespace Wordless\Contracts\Controller;

use Wordless\Exceptions\WordPressFailedToFindRole;
use WP_Error;
use WP_REST_Request;
use WP_Role;
use function get_role;

trait PermissionsChecks
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
     * @throws WordPressFailedToFindRole
     */
    public function registerCapabilitiesToRoles()
    {
        $capabilities = [
            $this->deletePermissionName(),
            $this->getItemsPermissionName(),
            $this->getItemPermissionName(),
            $this->createPermissionName(),
            $this->updatePermissionName(),
        ];

        foreach ($this->allowed_roles_names as $string_role) {
            if (($role = get_role($string_role)) instanceof WP_Role) {
                foreach ($capabilities as $capability) {
                    $role->add_cap($capability);
                }

                continue;
            }

            throw new WordPressFailedToFindRole($string_role);
        }
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

    private function updatePermissionName(): string
    {
        return "update_{$this->resourceName()}";
    }

    /**
     * @param string $capability
     * @return bool|WP_Error
     */
    private function resolvePermission(string $capability)
    {
        if (!$this->getCurrentUser()->can($capability)) {
            return $this->buildForbiddenContextError(__METHOD__);
        }

        return true;
    }
}
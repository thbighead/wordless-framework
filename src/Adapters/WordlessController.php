<?php

namespace Wordless\Adapters;

use Wordless\Contracts\Controller\ErrorHandling;
use Wordless\Contracts\Controller\PermissionsChecks;
use Wordless\Contracts\Controller\Routing;
use WP_REST_Controller;

abstract class WordlessController extends WP_REST_Controller
{
    use ErrorHandling, PermissionsChecks, Routing;

    private const FORBIDDEN_CONTEXT_CODE = 'rest_forbidden_context';
    private const FULL_SCHEMA_METHOD = 'get_item_schema';
    private const INVALID_METHOD_CODE = 'invalid-method';
    private const METHOD_NAME_TO_REST_DESTROY_ITEMS = 'delete_item';
    private const METHOD_NAME_TO_REST_INDEX_ITEMS = 'get_items';
    private const METHOD_NAME_TO_REST_SHOW_ITEMS = 'get_item';
    private const METHOD_NAME_TO_REST_STORE_ITEMS = 'create_item';
    private const METHOD_NAME_TO_REST_UPDATE_ITEMS = 'update_item';
    private const PERMISSION_METHOD_NAME_TO_REST_DESTROY_ITEMS = 'delete_item_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_INDEX_ITEMS = 'get_items_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_SHOW_ITEMS = 'get_item_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_STORE_ITEMS = 'create_item_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_UPDATE_ITEMS = 'update_item_permissions_check';
    private const PUBLIC_SCHEMA_METHOD = 'get_public_item_schema';

    protected ?array $allowed_roles_names = null;
    private ?User $user;

    abstract protected function resourceName(): string;

    abstract protected function version(): ?string;

    public function __construct()
    {
        $this->namespace = empty($this->version()) ?
            "/{$this->namespace()}" :
            "/{$this->namespace()}/{$this->version()}";
        $this->rest_base = $this->resourceName();
        $this->user = new User;

        if ($this->allowed_roles_names === null) {
            $this->allowed_roles_names = array_keys(wp_roles()->get_names());
        }
    }

    protected function namespace(): string
    {
        return 'wordless';
    }

    private function getCurrentUser(): ?User
    {
        return $this->user;
    }
}
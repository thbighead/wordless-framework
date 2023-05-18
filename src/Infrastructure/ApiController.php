<?php

namespace Wordless\Infrastructure;

use Generator;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\Str;
use Wordless\Contracts\Traits\Singleton;
use Wordless\Controller\Traits\AuthorizationCheck;
use Wordless\Controller\Traits\ResourceValidation;
use Wordless\Controller\Traits\RestingWordPress;
use Wordless\Controller\Traits\Routing;
use Wordless\Core\InternalCache;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\FailedToGetControllerPathFromCachedData;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\NoUserAuthenticated;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Wordpress\Models\User;
use WP_REST_Controller;

abstract class ApiController extends WP_REST_Controller
{
    use AuthorizationCheck, ResourceValidation, RestingWordPress, Routing, Singleton;

    /** @var bool[] */
    protected const AUTHENTICATION_PROTECTED_METHOD_ROUTES = [
        self::METHOD_NAME_TO_REST_DESTROY_ITEM => true,
        self::METHOD_NAME_TO_REST_INDEX_ITEMS => true,
        self::METHOD_NAME_TO_REST_SHOW_ITEM => true,
        self::METHOD_NAME_TO_REST_STORE_ITEM => true,
        self::METHOD_NAME_TO_REST_UPDATE_ITEM => true,
        'destroy' => true,
        'index' => true,
        'show' => true,
        'store' => true,
        'update' => true,
    ];
    protected const HAS_PERMISSIONS = false;
    private const FORBIDDEN_CONTEXT_CODE = 'rest_forbidden_context';
    private const INVALID_METHOD_CODE = 'invalid-method';
    private const METHOD_NAME_TO_REST_DESTROY_ITEM = 'delete_item';
    private const METHOD_NAME_TO_REST_INDEX_ITEMS = 'get_items';
    private const METHOD_NAME_TO_REST_SHOW_ITEM = 'get_item';
    private const METHOD_NAME_TO_REST_STORE_ITEM = 'create_item';
    private const METHOD_NAME_TO_REST_UPDATE_ITEM = 'update_item';
    private const PERMISSION_METHOD_NAME_TO_REST_DESTROY_ITEM = 'delete_item_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_INDEX_ITEMS = 'get_items_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_SHOW_ITEM = 'get_item_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_STORE_ITEM = 'create_item_permissions_check';
    private const PERMISSION_METHOD_NAME_TO_REST_UPDATE_ITEM = 'update_item_permissions_check';

    private ?User $authenticatedUser;

    abstract protected function resourceName(): string;

    abstract protected function version(): ?string;

    /**
     * @return Generator
     * @throws PathNotFoundException
     */
    public static function all(): Generator
    {
        try {
            $cached_controllers_data = InternalCache::getValueOrFail('controllers');

            foreach ($cached_controllers_data as $controller_full_namespace => $controller_cached_data) {
                $controller_pathing = $controller_cached_data['path'] ?? false;

                if (!$controller_pathing) {
                    throw new FailedToGetControllerPathFromCachedData($controller_cached_data);
                }

                yield [
                    $controller_cached_data['path'],
                    $controller_full_namespace,
                ];
            }
        } catch (FailedToFindCachedKey|FailedToGetControllerPathFromCachedData|InternalCacheNotLoaded $exception) {
            foreach (self::yieldBootableControllersPathAndResourceNameByReadingDirectory() as $controller_path_and_resource_name) {
                yield $controller_path_and_resource_name;
            }
        }
    }

    /**
     * @return Generator
     * @throws PathNotFoundException
     */
    public static function yieldBootableControllersPathAndResourceNameByReadingDirectory(): Generator
    {
        $controllers_directory_path = ProjectPath::controllers();

        foreach (DirectoryFiles::recursiveRead($controllers_directory_path) as $controller_path) {
            if (is_dir($controller_path)) {
                continue;
            }

            if (Str::endsWith($controller_path, 'Controller.php')) {
                $controller_relative_filepath_without_extension = trim(Str::after(
                    substr($controller_path, 0, -4), // Removes '.php'
                    $controllers_directory_path
                ), DIRECTORY_SEPARATOR);
                $controller_full_namespace = '\\App\\Controllers';

                foreach (explode(
                             DIRECTORY_SEPARATOR,
                             $controller_relative_filepath_without_extension
                         ) as $controller_pathing) {
                    $controller_full_namespace .= "\\$controller_pathing";
                }

                yield [$controller_path, $controller_full_namespace];
            }
        }
    }

    private function __construct()
    {
        $uri_namespace_prefix = "/{$this->namespace()}";
        $this->namespace = empty($this->version()) ?
            $uri_namespace_prefix :
            "/$uri_namespace_prefix/{$this->version()}";
        $this->rest_base = $this->resourceName();
        $this->setAuthenticatedUser();
    }

    protected function getAuthenticatedUser(): ?User
    {
        return $this->authenticatedUser;
    }

    protected function namespace(): string
    {
        return 'wordless';
    }

    protected function setAuthenticatedUser()
    {
        try {
            $this->authenticatedUser = new User;
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (NoUserAuthenticated $exception) {
            $this->authenticatedUser = null;
        }
    }
}

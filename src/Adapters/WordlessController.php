<?php

namespace Wordless\Adapters;

use Generator;
use Wordless\Abstractions\InternalCache;
use Wordless\Contracts\Controller\AuthorizationCheck;
use Wordless\Contracts\Controller\RestingWordPress;
use Wordless\Contracts\Controller\Routing;
use Wordless\Contracts\Singleton;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\FailedToGetControllerPathFromCachedData;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\NoUserAuthenticated;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;
use WP_REST_Controller;

abstract class WordlessController extends WP_REST_Controller
{
    use AuthorizationCheck, RestingWordPress, Routing, Singleton;

    protected const DESTROY_REQUEST_CLASS = Request::class;
    protected const INDEX_REQUEST_CLASS = Request::class;
    protected const SHOW_REQUEST_CLASS = Request::class;
    protected const STORE_REQUEST_CLASS = Request::class;
    protected const UPDATE_REQUEST_CLASS = Request::class;
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

    private ?User $authenticatedUser;

    abstract protected function resourceName(): string;

    abstract protected function version(): ?string;

    private function __construct()
    {
        $this->namespace = empty($this->version()) ?
            "/{$this->namespace()}" :
            "/{$this->namespace()}/{$this->version()}";
        $this->rest_base = $this->resourceName();
        $this->setAuthenticatedUser();
    }

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
        } catch (NoUserAuthenticated $exception) {
            $this->authenticatedUser = null;
        }
    }
}

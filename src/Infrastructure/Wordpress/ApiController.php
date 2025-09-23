<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Generator;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadErrorReportingConfiguration;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;
use Wordless\Infrastructure\Wordpress\ApiController\Traits\AuthorizationCheck;
use Wordless\Infrastructure\Wordpress\ApiController\Traits\ResourceValidation;
use Wordless\Infrastructure\Wordpress\ApiController\Traits\RestingWordPress;
use Wordless\Infrastructure\Wordpress\ApiController\Traits\Routing;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use WP_REST_Controller;

abstract class ApiController extends WP_REST_Controller
{
    use AuthorizationCheck;
    use Constructors;
    use ResourceValidation;
    use RestingWordPress;
    use Routing;

    final public const CACHE_PATH_KEY = 'path';
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
     * @throws FailedToLoadBootstrapper
     */
    public static function all(): Generator
    {
        try {
            $cached_controllers_data = InternalCache::getValueOrFail('controllers');

            foreach ($cached_controllers_data as $api_controller_namespace) {
                yield $api_controller_namespace;
            }
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded) {
            foreach (self::loadProvidedApiControllers() as $api_controller_namespace) {
                yield $api_controller_namespace;
            }
        }
    }

    /**
     * @return array
     * @throws FailedToLoadBootstrapper
     */
    public static function loadProvidedApiControllers(): array
    {
        return Bootstrapper::getInstance()->loadProvidedApiControllers();
    }

    private function __construct()
    {
        $this->namespace = empty($this->version()) ?
            $this->namespace() :
            "{$this->namespace()}/{$this->version()}";
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

    /**
     * @return void
     * @throws InvalidAcfFunction
     */
    protected function setAuthenticatedUser(): void
    {
        try {
            $this->authenticatedUser = new User(with_acfs: function_exists('get_fields'));
        } catch (NoUserAuthenticated) {
            $this->authenticatedUser = null;
        }
    }
}

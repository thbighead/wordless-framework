<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Wordpress\ApiController;

class ApiControllerCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'api_controllers.php';
    }

    /**
     * @return array
     * @throws FailedToLoadBootstrapper
     */
    protected function mountCacheArray(): array
    {
        $api_controllers_cache_array = [];

        foreach (ApiController::loadProvidedApiControllers() as $api_controller_namespace) {
            $api_controllers_cache_array[$api_controller_namespace] = $api_controller_namespace;
        }

        return $api_controllers_cache_array;
    }
}

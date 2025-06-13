<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Cachers\Exceptions\FailedToMountCacheArray;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadErrorReportingConfiguration;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Wordpress\ApiController;

class ApiControllerCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'api_controllers.php';
    }

    /**
     * @return string[]|ApiController[]
     * @throws FailedToMountCacheArray
     */
    protected function mountCacheArray(): array
    {
        $api_controllers_cache_array = [];

        try {
            foreach (ApiController::loadProvidedApiControllers() as $api_controller_namespace) {
                $api_controllers_cache_array[$api_controller_namespace] = $api_controller_namespace;
            }
        } catch (FailedToLoadErrorReportingConfiguration $exception) {
            throw new FailedToMountCacheArray($exception);
        }

        return $api_controllers_cache_array;
    }
}

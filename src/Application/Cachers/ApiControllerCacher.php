<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
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
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathException
     * @throws PathNotFoundException
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

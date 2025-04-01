<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\PackageProvider;

trait PublishConfigs
{
    private function loadProvidedConfigs(): array
    {
        $provided_config_paths = [];

        foreach ($this->loaded_providers as $provider) {
            if (!($provider instanceof PackageProvider)) {
                continue;
            }

            if (($config_filepath = $provider->registerConfig()) !== null) {
                $provided_config_paths[basename($config_filepath)] = $config_filepath;
            }
        }

        return $provided_config_paths;
    }

    /**
     * @return array<string, string>
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function bootProvidedConfigs(): array
    {
        return self::getInstance()->loadProvidedConfigs();
    }
}

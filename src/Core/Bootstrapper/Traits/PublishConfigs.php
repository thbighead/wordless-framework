<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
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
     * @return array
     * @throws PathNotFoundException
     * @throws InvalidProviderClass
     */
    public static function bootProvidedConfigs(): array
    {
        return self::getInstance()->loadProvidedConfigs();
    }
}

<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
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
     * @throws FailedToLoadBootstrapper
     */
    public static function bootProvidedConfigs(): array
    {
        return self::getInstance()->loadProvidedConfigs();
    }
}

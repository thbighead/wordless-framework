<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Cacher;

class ConfigCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'config.php';
    }

    /**
     * @return array
     * @throws InvalidConfigKey
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function mountCacheArray(): array
    {
        $cached_configs = [];

        foreach (DirectoryFiles::listFromDirectory(ProjectPath::config()) as $config_file) {
            $config_name = Str::before($config_file, '.php');
            $cached_configs[$config_name] = Config::get($config_name);
        }

        return $cached_configs;
    }
}

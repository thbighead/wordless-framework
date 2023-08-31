<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class ConfigCacher extends BaseCacher
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
            $cached_configs[$config_name] = Config::getFresh($config_name);
        }

        return $cached_configs;
    }
}

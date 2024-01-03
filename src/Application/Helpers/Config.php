<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

class Config
{
    use Internal;

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function get(string $key): mixed
    {
        try {
            return InternalCache::getValueOrFail("config.$key");
        } catch (InternalCacheNotLoaded|FailedToFindCachedKey) {
            return static::getFresh($key);
        }
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function getFresh(string $key): mixed
    {
        $keys = self::mountKeys($key);
        $config = self::retrieveConfig($keys);

        return self::searchKeysIntoConfig($keys, $config, $key);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws PathNotFoundException
     */
    public static function tryToGetOrDefault(string $key, mixed $default = null): mixed
    {
        try {
            return static::get($key);
        } catch (InvalidConfigKey) {
            return $default;
        }
    }
}

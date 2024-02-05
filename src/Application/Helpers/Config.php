<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Config\Contracts\Subjectable;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

class Config extends Subjectable
{
    use Internal;

    final public const FILE_WORDLESS = 'wordless';
    final public const FILE_WORDPRESS = 'wordpress';
    final public const KEY_CSP = 'csp';

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws PathNotFoundException
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return static::getOrFail($key);
        } catch (InvalidConfigKey) {
            return $default;
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
     * @return mixed
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function getOrFail(string $key): mixed
    {
        try {
            return InternalCache::getValueOrFail("config.$key");
        } catch (InternalCacheNotLoaded|FailedToFindCachedKey) {
            return static::getFresh($key);
        }
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws PathNotFoundException
     */
    public static function wordless(?string $key = null, mixed $default = null): mixed
    {
        return self::fromConfigFile(self::FILE_WORDLESS, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed|ConfigSubjectDTO
     * @throws PathNotFoundException
     */
    public static function wordpress(?string $key = null, mixed $default = null): mixed
    {
        return self::fromConfigFile(self::FILE_WORDPRESS, $key, $default);
    }
}

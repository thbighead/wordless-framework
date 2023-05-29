<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

class Config
{
    private const SEPARATOR = '.';
    private const WILDCARD = '*';

    private static array $configs = [];

    /**
     * @param string $key
     * @return array|mixed
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public static function get(string $key)
    {
        try {
            return InternalCache::getValueOrFail("config.$key");
        } catch (InternalCacheNotLoaded|FailedToFindCachedKey $exception) {
            $keys = self::mountKeys($key);
            $config = self::retrieveConfig($keys);

            return self::searchKeysIntoConfig($keys, $config, $key);
        }
    }

    /**
     * @param string $key
     * @param $default
     * @return array|mixed|null
     * @throws PathNotFoundException
     */
    public static function tryToGetOrDefault(string $key, $default = null): mixed
    {
        try {
            return self::get($key);
        } catch (InvalidConfigKey $exception) {
            return $default;
        }
    }

    /**
     * @param string $config_filename_without_extension
     * @return mixed
     * @throws PathNotFoundException
     */
    private static function loadConfigFile(string $config_filename_without_extension)
    {
        return include ProjectPath::config("$config_filename_without_extension.php");
    }

    /**
     * @param string $keys_as_string
     * @return array
     * @throws InvalidConfigKey
     */
    private static function mountKeys(string $keys_as_string): array
    {
        $keys = explode(self::SEPARATOR, $keys_as_string);

        if (empty($keys)) {
            throw new InvalidConfigKey($keys_as_string);
        }

        return $keys;
    }

    /**
     * @param int $key_index
     * @param $key_pointer
     * @param array $keys
     * @param string $original_keys_as_string
     * @return array
     * @throws InvalidConfigKey
     */
    private static function resolveWildcard(
        int    $key_index,
               $key_pointer,
        array  $keys,
        string $original_keys_as_string
    ): array
    {
        if (!is_array($key_pointer)) {
            throw new InvalidConfigKey($original_keys_as_string);
        }

        $result = [];

        foreach ($key_pointer as $wildcarded_item) {
            $result[] = self::searchKeysIntoConfig(
                array_slice($keys, $key_index + 1),
                $wildcarded_item,
                $original_keys_as_string
            );
        }

        return $result;
    }

    /**
     * @param array $keys
     * @return mixed
     * @throws PathNotFoundException
     */
    private static function retrieveConfig(array &$keys)
    {
        $config_filename_without_extension = array_shift($keys);

        return self::$configs[$config_filename_without_extension] ??
            self::$configs[$config_filename_without_extension] = self::loadConfigFile(
                $config_filename_without_extension
            );
    }

    /**
     * @param array $keys
     * @param array $config
     * @param string $original_keys_as_string
     * @return array|mixed
     * @throws InvalidConfigKey
     */
    private static function searchKeysIntoConfig(array $keys, array $config, string $original_keys_as_string)
    {
        $pointer = $config;

        foreach ($keys as $index => $parsed_key) {
            if ($parsed_key === self::WILDCARD) {
                return self::resolveWildcard($index, $pointer, $keys, $original_keys_as_string);
            }

            if (!isset($pointer[$parsed_key])) {
                throw new InvalidConfigKey($original_keys_as_string);
            }

            $pointer = $pointer[$parsed_key];
        }

        return $pointer;
    }
}

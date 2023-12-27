<?php

namespace Wordless\Core;

use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;
use Wordless\Core\InternalCache\Exceptions\InvalidCache;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

final class InternalCache
{
    public const INTERNAL_WORDLESS_CACHE_CONSTANT_NAME = 'INTERNAL_WORDLESS_CACHE';
    private const PHP_EXTENSION = '.php';

    /**
     * @return void
     * @throws InvalidProviderClass
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     */
    public static function generate(): void
    {
        foreach (Bootstrapper::getInstance()->loadProvidedInternalCachers() as $internal_cacher_namespace) {
            $internal_cacher_namespace::generate();
        }
    }

    /**
     * @param string $key_pathing
     * @param $default
     * @return mixed|null
     * @throws InternalCacheNotLoaded
     */
    public static function getValue(string $key_pathing, $default = null): mixed
    {
        try {
            return self::getValueOrFail($key_pathing);
        } catch (FailedToFindCachedKey) {
            return $default;
        }
    }

    /**
     * @param string $key_pathing_string
     * @return mixed
     * @throws FailedToFindCachedKey
     * @throws InternalCacheNotLoaded
     */
    public static function getValueOrFail(string $key_pathing_string): mixed
    {
        if (!self::isLoaded()) {
            throw new InternalCacheNotLoaded($key_pathing_string);
        }

        $key_pathing = explode('.', $key_pathing_string);
        $first_key = array_shift($key_pathing);

        $pointer = INTERNAL_WORDLESS_CACHE[$first_key] ?? throw new FailedToFindCachedKey(
            $key_pathing_string,
            $first_key
        );

        foreach ($key_pathing as $key) {
            $pointer = $pointer[$key] ?? throw new FailedToFindCachedKey($key_pathing_string, $key);
        }

        return $pointer;
    }

    /**
     * @return void
     * @throws InvalidCache
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function load(): void
    {
        if (Environment::isNotLocal() && !self::isLoaded()) {
            define(self::INTERNAL_WORDLESS_CACHE_CONSTANT_NAME, self::retrieveCachedValues());
        }
    }

    private static function isLoaded(): bool
    {
        return defined(self::INTERNAL_WORDLESS_CACHE_CONSTANT_NAME);
    }

    /**
     * @return array
     * @throws InvalidDirectory
     * @throws InvalidCache
     * @throws PathNotFoundException
     */
    private static function retrieveCachedValues(): array
    {
        $internal_wordless_cache = [];

        foreach (DirectoryFiles::recursiveRead(ProjectPath::cache()) as $cache_file_path) {
            if (Str::endsWith($cache_file_path, '.gitignore')) {
                continue;
            }

            if (is_dir($cache_file_path) || !Str::endsWith($cache_file_path, self::PHP_EXTENSION)) {
                throw new InvalidCache($cache_file_path, 'Cache directory must have only PHP files.');
            }

            $internal_wordless_cache[Str::before(
                Str::afterLast($cache_file_path, DIRECTORY_SEPARATOR),
                self::PHP_EXTENSION
            )] = require $cache_file_path;
        }

        return $internal_wordless_cache;
    }
}

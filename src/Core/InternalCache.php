<?php declare(strict_types=1);

namespace Wordless\Core;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;
use Wordless\Core\InternalCache\Exceptions\InvalidCache;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Core\Exceptions\DotEnvNotSetException;

final class InternalCache
{
    private const PHP_EXTENSION = '.php';

    private static array $internal_wordless_cache = [];
    private static bool $internal_wordless_cache_loaded = false;

    /**
     * @return void
     * @throws FailedToDeletePath
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function clean(): void
    {
        foreach (DirectoryFiles::listFromDirectory(ProjectPath::cache()) as $supposed_cache_file) {
            if (!Str::endsWith($supposed_cache_file, '.php')) {
                continue;
            }

            DirectoryFiles::delete(ProjectPath::cache($supposed_cache_file));
        }

        self::flush();
    }

    public static function flush(): void
    {
        self::$internal_wordless_cache = [];
        self::$internal_wordless_cache_loaded = false;
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathException
     * @throws PathNotFoundException
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

        $pointer = self::$internal_wordless_cache[$first_key] ?? throw new FailedToFindCachedKey(
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
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws InvalidCache
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     * @throws PathException
     */
    public static function load(): void
    {
        if (Environment::isNotLocal() && !self::isLoaded()) {
            self::$internal_wordless_cache = self::retrieveCachedValues();
            self::$internal_wordless_cache_loaded = true;
        }
    }

    private static function isLoaded(): bool
    {
        return self::$internal_wordless_cache_loaded;
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
            $internal_wordless_cache[Str::of($cache_file_path)
                ->afterLast(DIRECTORY_SEPARATOR)
                ->before(self::PHP_EXTENSION)
                ->getSubject()] = require $cache_file_path;
        }

        return $internal_wordless_cache;
    }
}

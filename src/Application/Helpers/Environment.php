<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToFindPackagesMarkerInsideEnvFile;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\Environment\Traits\Internal;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;
use Wordless\Infrastructure\Helper;

class Environment extends Helper
{
    use Internal;

    final public const DOT_ENV_COMMENT_MARK = '#';
    final public const LOCAL = 'local';
    final public const PACKAGES_MARKER = <<<STRING
###########################################################
##################### Packages Setup ######################
###########################################################

STRING;
    final public const PRODUCTION = 'production';
    final public const STAGING = 'staging';
    private const DOT_ENV_LOADED_CONSTANT_NAME = 'DOT_ENV_LOADED';

    /**
     * @return string
     * @throws FailedToCopyDotEnvExampleIntoNewDotEnv
     * @throws PathNotFoundException
     */
    public static function createDotEnvFromExample(): string
    {
        try {
            return ProjectPath::root('.env');
        } catch (PathNotFoundException $pathNotFoundException) {
            $new_dot_env_filepath = $pathNotFoundException->path;

            try {
                DirectoryFiles::copyFile(
                    ProjectPath::root('.env.example'),
                    $new_dot_env_filepath,
                    false
                );
            } catch (FailedToCopyFile $failedToCopyFileException) {
                throw new FailedToCopyDotEnvExampleIntoNewDotEnv(
                    $failedToCopyFileException->from,
                    $failedToCopyFileException->to,
                    $failedToCopyFileException->secure_mode
                );
            }

            return ProjectPath::realpath($new_dot_env_filepath);
        }
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::loadDotEnv();

        try {
            $value = InternalCache::getValueOrFail("environment.$key");
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded) {
            $value = self::retrieveValue($key, $default);
        }

        return self::returnTypedValue($value);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function getWithoutCache(string $key, mixed $default = null): mixed
    {
        if (!defined(self::DOT_ENV_LOADED_CONSTANT_NAME)) {
            self::loadDotEnv();
        }

        return self::returnTypedValue(self::retrieveValue($key, $default));
    }

    public static function isCli(): bool
    {
        if (defined('STDIN')) {
            return true;
        }

        if (php_sapi_name() === 'cli') {
            return true;
        }

        if (array_key_exists('SHELL', $_ENV)) {
            return true;
        }

        if (empty($_SERVER['REMOTE_ADDR'] ?? null) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0) {
            return true;
        }

        if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
            return true;
        }

        return false;
    }

    public static function isFramework(): bool
    {
        return defined('FRAMEWORK_ENVIRONMENT') && FRAMEWORK_ENVIRONMENT === true;
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isLocal(): bool
    {
        return static::get('APP_ENV') === self::LOCAL;
    }

    public static function isNotCli(): bool
    {
        return !static::isCli();
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isNotLocal(): bool
    {
        return !static::isLocal();
    }

    public static function isNotFramework(): bool
    {
        return !static::isFramework();
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isNotProduction(): bool
    {
        return !static::isProduction();
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isNotRemote(): bool
    {
        return !static::isRemote();
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isNotStaging(): bool
    {
        return !static::isStaging();
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isProduction(): bool
    {
        return static::get('APP_ENV') === self::PRODUCTION;
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isRemote(): bool
    {
        return static::isProduction() && static::isStaging();
    }

    /**
     * @return bool
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function isStaging(): bool
    {
        return static::get('APP_ENV') === self::STAGING;
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    public static function loadDotEnv(): void
    {
        if (defined(self::DOT_ENV_LOADED_CONSTANT_NAME)) {
            return;
        }

        try {
            (new Dotenv)->load(ProjectPath::root('.env'));
            define(self::DOT_ENV_LOADED_CONSTANT_NAME, true);
        } catch (PathNotFoundException $exception) {
            throw new DotEnvNotSetException(".env file not found at $exception->path");
        }
    }

    /**
     * @param string $package_name
     * @param array<string|int, ?string> $variables
     * @param bool $to_env_example
     * @return void
     * @throws FailedToFindPackagesMarkerInsideEnvFile
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     */
    public static function writeNewPackageVariables(
        string $package_name,
        array  $variables,
        bool   $to_env_example = true
    ): void
    {
        if (empty($package_variables_content = self::mountPackageVariablesContentToDotEnv($variables))) {
            return;
        }

        $package_variables_content = "# $package_name" . PHP_EOL . $package_variables_content;
        $dot_env_filepath = ProjectPath::root($to_env_example ? '.env.example' : '.env');

        if (!Str::contains($dot_env_content = file_get_contents($dot_env_filepath), self::PACKAGES_MARKER)) {
            throw new FailedToFindPackagesMarkerInsideEnvFile($dot_env_filepath);
        }

        $dot_env_content = Str::replace($dot_env_content, self::PACKAGES_MARKER, $package_variables_content);

        self::rewriteDotEnvFile($dot_env_filepath, $dot_env_content);
    }

    /**
     * @param string $dot_env_filepath
     * @param string $dot_env_new_content
     * @return void
     * @throws FailedToRewriteDotEnvFile
     */
    final public static function rewriteDotEnvFile(string $dot_env_filepath, string $dot_env_new_content): void
    {
        if (file_put_contents($dot_env_filepath, $dot_env_new_content) === false) {
            throw new FailedToRewriteDotEnvFile($dot_env_filepath, $dot_env_new_content);
        }
    }
}

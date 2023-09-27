<?php

namespace Wordless\Application\Helpers;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Commands\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToFindPackagesMarkerInsideEnvFile;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\Environment\Traits\Internal;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

class Environment
{
    use Internal;

    final public const COMMONLY_DOT_ENV_DEFAULT_VALUES = [
        'APP_NAME' => 'Wordless App',
        'APP_ENV' => 'local',
        'APP_URL' => 'https://wordless-app.dev.br',
        'FRONT_END_URL' => 'https://wordless-front.dev.br',
        'DB_NAME' => 'wordless',
        'DB_USER' => 'root',
        'DB_HOST' => '127.0.0.1',
        'DB_CHARSET' => 'utf8mb4',
        'DB_COLLATE' => 'utf8mb4_unicode_ci',
        'DB_TABLE_PREFIX' => 'null',
        'WORDLESS_CSP' => 'null',
        'WP_VERSION' => 'null',
        'WP_THEME' => 'null',
        'WP_PERMALINK' => 'null',
        'WP_DEBUG' => 'true',
        'WP_LANGUAGES' => 'en_US',
    ];
    final public const DOT_ENV_COMMENT_MARK = '#';
    final public const LOCAL = 'local';
    final public const PACKAGES_MARKER = <<<STRING
###########################################################
##################### Packages Setup ######################
###########################################################

STRING;
    final public const PRODUCTION = 'production';
    final public const STAGING = 'staging';

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
            $new_dot_env_filepath = $pathNotFoundException->getPath();

            try {
                DirectoryFiles::copyFile(
                    ProjectPath::root('.env.example'),
                    $new_dot_env_filepath,
                    false
                );
            } catch (FailedToCopyFile $failedToCopyFileException) {
                throw new FailedToCopyDotEnvExampleIntoNewDotEnv(
                    $failedToCopyFileException->getFrom(),
                    $failedToCopyFileException->getTo(),
                    $failedToCopyFileException->getSecureMode()
                );
            }

            return ProjectPath::realpath($new_dot_env_filepath);
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = InternalCache::getValueOrFail("environment.$key");
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded) {
            $value = self::retrieveValue($key, $default);
        }

        return self::returnTypedValue($value);
    }

    public static function isLocal(): bool
    {
        return static::get('APP_ENV') === self::LOCAL;
    }

    public static function isNotLocal(): bool
    {
        return !static::isLocal();
    }

    public static function isNotProduction(): bool
    {
        return !static::isProduction();
    }

    public static function isNotStaging(): bool
    {
        return !static::isStaging();
    }

    public static function isProduction(): bool
    {
        return static::get('APP_ENV') === self::PRODUCTION;
    }

    public static function isStaging(): bool
    {
        return static::get('APP_ENV') === self::STAGING;
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    public static function loadDotEnv(): void
    {
        try {
            (new Dotenv)->load(ProjectPath::root('.env'));
        } catch (PathNotFoundException $exception) {
            throw new DotEnvNotSetException(".env file not found at {$exception->getPath()}");
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

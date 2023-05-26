<?php

namespace Wordless\Application\Helpers;

use Symfony\Component\Dotenv\Dotenv;
use Wordless\Application\Commands\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToFindPackagesMarkerInsideEnvFile;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

class Environment
{
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

    /**
     * @param string $value
     * @return string[]
     */
    private static function findReferenceInValue(string $value): array
    {
        preg_match_all('/^\S+=[^\s"]*\$\{(.+)}[^\s"]*$/m', $value, $output_array);

        return $output_array[1] ?? [];
    }

    private static function mountPackageVariablesContentToDotEnv(array $variables): string
    {
        $package_variables_content = '';

        foreach ($variables as $variable_name => $variable_value) {
            if (!is_string($variable_name)) {
                $package_variables_content .=
                    $variable_value !== null ? "$variable_value=#$variable_value" . PHP_EOL : '';
                continue;
            }

            if ($variable_value === null) {
                continue;
            }

            $package_variables_content .= "$variable_name=$variable_value" . PHP_EOL;
        }

        return $package_variables_content;
    }

    private static function resolveReferences(string $value): string
    {
        do {
            $referenced_dot_env_variable_names = self::findReferenceInValue($value);

            foreach ($referenced_dot_env_variable_names as $referenced_dot_env_variable_name) {
                $value = preg_replace(
                    '/^(\S+=[^\s"]*)\$\{.+}([^\s"]*)$/m',
                    '$1' . self::get($referenced_dot_env_variable_name) . '$2',
                    $value
                );
            }
        } while (!empty($referenced_dot_env_variable_names));

        return $value;
    }

    private static function retrieveValue(string $key, mixed $default = null): mixed
    {
        if (($value = getenv($key)) === false) {
            $value = $_ENV[$key] ?? $default;
        }

        if (!is_string($value)) {
            return $value;
        }

        return self::resolveReferences($value);
    }

    private static function returnTypedValue(mixed $value): mixed
    {
        return match (strtoupper($value)) {
            'TRUE' => true,
            'FALSE' => false,
            'NULL' => null,
            default => $value,
        };
    }
}

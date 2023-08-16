<?php

namespace Wordless\Helpers;

use Symfony\Component\Dotenv\Dotenv;
use Wordless\Abstractions\InternalCache;
use Wordless\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\FailedToFindPackagesMarkerInsideEnvFile;
use Wordless\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Exceptions\FailedToWriteInFile;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\PathNotFoundException;

class Environment
{
    public const COMMONLY_DOT_ENV_DEFAULT_VALUES = [
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
    public const DOT_ENV_COMMENT_MARK = '#';
    public const LOCAL = 'local';
    public const PACKAGES_MARKER = <<<STRING
###########################################################
##################### Packages Setup ######################
###########################################################

STRING;
    public const PRODUCTION = 'production';
    public const STAGING = 'staging';

    /**
     * @return string
     * @throws FailedToCopyDotEnvExampleIntoNewDotEnv
     * @throws PathNotFoundException
     */
    public static function createDotEnvFromExample(): string
    {
        try {
            return ProjectPath::root('.env');
        } catch (PathNotFoundException $exception) {
            $new_dot_env_filepath = $exception->getPath();

            if (!copy(
                $dot_env_example_filepath = ProjectPath::root('.env.example'),
                $new_dot_env_filepath
            )) {
                throw new FailedToCopyDotEnvExampleIntoNewDotEnv(
                    $dot_env_example_filepath,
                    $new_dot_env_filepath
                );
            }

            return ProjectPath::realpath($new_dot_env_filepath);
        }
    }

    public static function get(string $key, $default = null)
    {
        try {
            $value = InternalCache::getValueOrFail("environment.$key");
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded $exception) {
            $value = self::retrieveValue($key, $default);
        }

        return self::returnTypedValue($value);
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function loadDotEnv()
    {
        (new Dotenv)->load(ProjectPath::root('.env'));
    }

    public static function isLocal(): bool
    {
        return self::get('APP_ENV') === Environment::LOCAL;
    }

    public static function isNotLocal(): bool
    {
        return !self::isLocal();
    }

    public static function isNotProduction(): bool
    {
        return !self::isProduction();
    }

    public static function isNotStaging(): bool
    {
        return !self::isStaging();
    }

    public static function isProduction(): bool
    {
        return self::get('APP_ENV') === Environment::PRODUCTION;
    }

    public static function isStaging(): bool
    {
        return self::get('APP_ENV') === Environment::STAGING;
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
    )
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

        if (file_put_contents($dot_env_filepath, $dot_env_content) === false) {
            throw new FailedToRewriteDotEnvFile($dot_env_filepath, $dot_env_filepath);
        }
    }

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

    private static function resolveReferences(string $value)
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

    private static function retrieveValue(string $key, $default = null)
    {
        if (($value = getenv($key)) === false) {
            $value = $_ENV[$key] ?? $default;
        }

        if (!is_string($value)) {
            return $value;
        }

        return self::resolveReferences($value);
    }

    private static function returnTypedValue($value)
    {
        switch (strtoupper($value)) {
            case 'TRUE':
                return true;
            case 'FALSE':
                return false;
            case 'NULL':
                return null;
        }

        return $value;
    }
}

<?php

namespace Wordless\Helpers;

use Symfony\Component\Dotenv\Dotenv;
use Wordless\Abstractions\InternalCache;
use Wordless\Exceptions\FailedToFindCachedKey;
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
    public const DOT_ENV_REFERENCE_PREFIX_MARK = '${';
    public const DOT_ENV_REFERENCE_SUFFIX_MARK = '}';
    public const LOCAL = 'local';
    public const PRODUCTION = 'production';
    public const STAGING = 'staging';

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

    private static function retrieveValue(string $key, $default = null)
    {
        if (($value = getenv($key)) === false) {
            $value = $_ENV[$key] ?? $default;
        }

        while(is_string($value) && Str::isSurroundedBy(
            $value,
            self::DOT_ENV_REFERENCE_PREFIX_MARK,
            self::DOT_ENV_REFERENCE_SUFFIX_MARK
        )) {
            $value = self::retrieveValue(Str::between(
                $value,
                self::DOT_ENV_REFERENCE_PREFIX_MARK,
                self::DOT_ENV_REFERENCE_SUFFIX_MARK
            ), $default);
        }

        return $value;
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
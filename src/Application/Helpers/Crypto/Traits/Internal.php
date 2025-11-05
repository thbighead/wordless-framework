<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Crypto\Traits;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;

trait Internal
{
    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function hashedIv(): string
    {
        return substr(hash(self::HASH_ALGORITHM, self::secretIv()), 0, self::HASHED_IV_LENGTH);
    }

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function hashedKey(): string
    {
        return hash(self::HASH_ALGORITHM, self::secretKey());
    }

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function secretIv(): string
    {
        return Environment::get('SECURE_AUTH_SALT', SECURE_AUTH_SALT);
    }

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    private static function secretKey(): string
    {
        return Environment::get('SECURE_AUTH_KEY', SECURE_AUTH_KEY);
    }
}

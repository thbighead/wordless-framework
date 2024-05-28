<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Crypto\Traits;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait Internal
{
    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private static function hashedIv(): string
    {
        return substr(hash(self::HASH_ALGORITHM, self::secretIv()), 0, self::HASHED_IV_LENGTH);
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private static function hashedKey(): string
    {
        return hash(self::HASH_ALGORITHM, self::secretKey());
    }

    /**
     * @return string
     * @throws FormatException
     * @throws DotEnvNotSetException
     */
    private static function secretIv(): string
    {
        return Environment::get('SECURE_AUTH_SALT');
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private static function secretKey(): string
    {
        return Environment::get('SECURE_AUTH_KEY');
    }
}

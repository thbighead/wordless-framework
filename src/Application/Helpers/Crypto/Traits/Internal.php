<?php

namespace Wordless\Application\Helpers\Crypto\Traits;

use Wordless\Application\Helpers\Environment;

trait Internal
{
    private static function hashedIv(): string
    {
        return substr(hash(self::HASH_ALGORITHM, self::secretIv()), 0, self::HASHED_IV_LENGTH);
    }

    private static function hashedKey(): string
    {
        return hash(self::HASH_ALGORITHM, self::secretKey());
    }

    private static function secretIv(): string
    {
        return Environment::get('SECURE_AUTH_SALT');
    }

    private static function secretKey(): string
    {
        return Environment::get('SECURE_AUTH_KEY');
    }
}

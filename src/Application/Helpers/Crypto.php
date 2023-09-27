<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Crypto\Traits\Internal;

class Crypto
{
    use Internal;

    public const HASH_ALGORITHM = 'sha256';
    public const HASHED_IV_LENGTH = 16;
    public const OPENSSL_CIPHER_ALGORITHM = 'AES-256-CBC';

    public static function decrypt(string $string_to_decrypt): bool|string
    {
        return openssl_decrypt(
            base64_decode($string_to_decrypt),
            self::OPENSSL_CIPHER_ALGORITHM,
            self::hashedKey(),
            0,
            self::hashedIv()
        );
    }

    public static function encrypt(string $string_to_encrypt): string
    {
        return base64_encode(openssl_encrypt(
            $string_to_encrypt,
            self::OPENSSL_CIPHER_ALGORITHM,
            self::hashedKey(),
            0,
            self::hashedIv()
        ));
    }
}

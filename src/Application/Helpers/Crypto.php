<?php

namespace Wordless\Application\Helpers;

class Crypto
{
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

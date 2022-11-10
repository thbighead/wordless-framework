<?php

namespace Wordless\Helpers;

class Crypto
{
    public const JWT_SYMMETRIC_HMAC_SHA256 = 'HS256';
    public const JWT_SYMMETRIC_HMAC_SHA384 = 'HS384';
    public const JWT_SYMMETRIC_HMAC_SHA512 = 'HS512';
    public const JWT_SYMMETRIC_HMAC_BLAKE2B_HASH = 'BLAKE2B';

    public const JWT_ASYMMETRIC_RSA_SSA_PKCS1_V1_5_SHA256 = 'RS256';
    public const JWT_ASYMMETRIC_RSA_SSA_PKCS1_V1_5_SHA384 = 'RS384';
    public const JWT_ASYMMETRIC_RSA_SSA_PKCS1_V1_5_SHA512 = 'RS512';
    public const JWT_ASYMMETRIC_ED_DSA = 'EdDSA';

    public const HASH_ALGORITHM = 'sha256';
    public const HASHED_IV_LENGTH = 16;
    public const OPENSSL_CIPHER_ALGORITHM = 'AES-256-CBC';

    public static function decrypt(string $string_to_decrypt)
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

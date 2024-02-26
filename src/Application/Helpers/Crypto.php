<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Crypto\Traits\Base64;
use Wordless\Application\Helpers\Crypto\Traits\Base64\Exceptions\FailedToDecode;
use Wordless\Application\Helpers\Crypto\Traits\Internal;
use Wordless\Infrastructure\Helper;

class Crypto extends Helper
{
    use Base64;
    use Internal;

    public const HASH_ALGORITHM = 'sha256';
    public const HASHED_IV_LENGTH = 16;
    public const OPENSSL_CIPHER_ALGORITHM = 'AES-256-CBC';

    /**
     * @param string $string_to_decrypt
     * @return bool|string
     * @throws FailedToDecode
     */
    public static function decrypt(string $string_to_decrypt): bool|string
    {
        return openssl_decrypt(
            self::base64Decode($string_to_decrypt),
            self::OPENSSL_CIPHER_ALGORITHM,
            self::hashedKey(),
            0,
            self::hashedIv()
        );
    }

    public static function encrypt(string $string_to_encrypt): string
    {
        return self::base64Encode(openssl_encrypt(
            $string_to_encrypt,
            self::OPENSSL_CIPHER_ALGORITHM,
            self::hashedKey(),
            0,
            self::hashedIv()
        ));
    }
}

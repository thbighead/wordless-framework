<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Crypto\Traits;

use Wordless\Application\Helpers\Crypto\Traits\Base64\Exceptions\FailedToDecode;

trait Base64
{
    /**
     * @param string $string_to_decode
     * @param bool $ignore_invalid_characters
     * @return string
     * @throws FailedToDecode
     */
    final public static function base64Decode(
        string $string_to_decode,
        bool   $ignore_invalid_characters = false
    ): string
    {
        if (!is_string($decoded_string = base64_decode($string_to_decode, $ignore_invalid_characters))) {
            throw new FailedToDecode($string_to_decode, $ignore_invalid_characters);
        }

        return $decoded_string;
    }

    final public static function base64Encode(string $string_to_encode): string
    {
        return base64_encode($string_to_encode);
    }
}

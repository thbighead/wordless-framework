<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT\Enums;

enum CryptoAlgorithm: string
{
    case symmetric_hmac_sha256 = 'HS256';
    case symmetric_hmac_sha384 = 'HS384';
    case symmetric_hmac_sha512 = 'HS512';
    case symmetric_hmac_blake2b_hash = 'BLAKE2B';
}

<?php

namespace Wordless\Application\JWT\Enums;

enum CryptoAlgorithm: string
{
    case SYMMETRIC_HMAC_SHA256 = 'HS256';
    case SYMMETRIC_HMAC_SHA384 = 'HS384';
    case SYMMETRIC_HMAC_SHA512 = 'HS512';
    case SYMMETRIC_HMAC_BLAKE2B_HASH = 'BLAKE2B';
}

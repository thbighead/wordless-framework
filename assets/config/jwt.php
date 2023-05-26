<?php

use Wordless\Application\Helpers\Crypto;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\JWT\Enums\CryptoAlgorithm;
use Wordless\Application\JWT\Token;

return [
    Token::CONFIG_DEFAULT_CRYPTO => CryptoAlgorithm::SYMMETRIC_HMAC_SHA256,
    Token::CONFIG_SIGN_KEY => Environment::get(Token::ENVIRONMENT_SIGN_VARIABLE),
];

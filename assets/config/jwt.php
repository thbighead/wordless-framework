<?php

use Wordless\Application\Helpers\Crypto;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\JsonWebToken;

return [
    JsonWebToken::CONFIG_DEFAULT_CRYPTO => Crypto::JWT_SYMMETRIC_HMAC_SHA256,
    JsonWebToken::CONFIG_SIGN_KEY => Environment::get(JsonWebToken::ENVIRONMENT_SIGN_VARIABLE),
];

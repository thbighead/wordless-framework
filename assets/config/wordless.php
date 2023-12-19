<?php

use Wordless\Application\Commands\DatabaseOverwrite\DTO\UserDTO;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Libraries\JWT\Enums\CryptoAlgorithm;
use Wordless\Application\Libraries\JWT\Token;
use Wordless\Infrastructure\Provider;

$current_wp_theme = Config::tryToGetOrDefault('wordpress.theme', 'wordless');
/** @var Provider[] $providers */
$providers = [];

return [
    'csp' => [
        'default-src' => ['self' => true],
        'frame-ancestors' => [],
        'upgrade-insecure-requests' => true,
    ],
    'database' => [
        UserDTO::USER_DEFAULT_PASSWORD_KEY => 'password',
    ],
    'jwt' => [
        Token::CONFIG_DEFAULT_CRYPTO => CryptoAlgorithm::SYMMETRIC_HMAC_SHA256,
        Token::CONFIG_SIGN_KEY => Environment::get(Token::ENVIRONMENT_SIGN_VARIABLE),
    ],
    'providers' => $providers,
    'public-symlinks' => [
        'index.php' => '../wp/index.php',
        'wp-content/plugins' => '../wp/wp-content/plugins!.gitignore',
        "wp-content/themes/$current_wp_theme/public" => "../wp/wp-content/themes/$current_wp_theme/public",
        'wp-content/uploads' => '../wp/wp-content/uploads',
        'wp-core' => '../wp/wp-core!wp-config.php,xmlrpc.php',
    ],
];

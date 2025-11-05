<?php declare(strict_types=1);

namespace Wordless\Tests;

use PHPUnit\Framework\TestCase;
use Wordless\Application\Libraries\JWT\Token;
use Wordless\Tests\Unit\JwtTest;

abstract class WordlessTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $_ENV[Token::ENVIRONMENT_SIGN_VARIABLE] ??= base64_encode(JwtTest::JWT_4096_KEY);

        require_once __DIR__ . '/../wp/wp-core/wp-config.php';

        parent::setUpBeforeClass();
    }
}

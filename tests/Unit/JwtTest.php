<?php /** @noinspection SpellCheckingInspection */

namespace Wordless\Tests\Unit;

use DateTimeImmutable;
use Exception;
use Wordless\Application\Helpers\Crypto;
use Wordless\Application\JsonWebToken;
use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\InvalidJwtCryptoAlgorithmId;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Tests\WordlessTestCase;

class JwtTest extends WordlessTestCase
{
    private const JWT_4096_KEY = 'p3s6v9y$B&E)H@McQfTjWnZq4t7w!z%C*F-JaNdRgUkXp2s5u8x/A?D(G+KbPeShVmYq3t6w9y$B&E)H@McQfTjWnZr4u7x!A%C*F-JaNdRgUkXp2s5v8y/B?E(G+KbPeShVmYq3t6w9z$C&F)J@McQfTjWnZr4u7x!A%D*G-KaPdRgUkXp2s5v8y/B?E(H+MbQeThVmYq3t6w9z$C&F)J@NcRfUjXnZr4u7x!A%D*G-KaPdSgVkYp3s5v8y/B?E(H+MbQeThWmZq4t7w9z$C&F)J@NcRfUjXn2r5u8x/A%D*G-KaPdSgVkYp3s6v9y$B&E(H+MbQeThWmZq4t7w!z%C*F-J@NcRfUjXn2r5u8x/A?D(G+KbPdSgVkYp3s6v9y$B&E)H@McQfThWmZq4t7w!z%C*F-JaNdRgUkXn2r5u8x/A?D(G+KbPeShVmYq3s6v9y$B&E)H@McQfTjWnZr4u7w!z%C*F-JaNdRgUkXp2s5v8y/A?D(G+KbPeShVm';
    private const JWT_EXAMPLE = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.FLRNObywMeKXndJLSDSZ8MU4dQ_nrfHb3_m4d2jLkgc';
    private const JWT_TEST_PAYLOAD = [
        'this' => 'is',
        'a' => 'freaking',
        'test',
        4 => 'us',
    ];

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        define('ROOT_PROJECT_PATH', __DIR__ . '/../../src');
        $_ENV[JsonWebToken::ENVIRONMENT_SIGN_VARIABLE] = base64_encode(self::JWT_4096_KEY);
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     * @throws Exception
     */
    public function testParsingTokenConstructor()
    {
        $jwt = new JsonWebToken(self::JWT_EXAMPLE);

        $this->assertEquals([
            'alg' => Crypto::JWT_SYMMETRIC_HMAC_SHA256,
            'typ' => 'JWT',
        ], $jwt->getDecodedHeader());

        $this->assertEquals([
            'sub' => '1234567890',
            'name' => 'John Doe',
            'iat' => (new DateTimeImmutable)->setTimestamp(1516239022),
        ], $jwt->getDecodedPayload());

        $jwt->validateSignature();
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function testPayloadUsingDefaultCryptoConstructor()
    {
        $this->assertEquals(
            self::JWT_TEST_PAYLOAD,
            ($jwt = new JsonWebToken(self::JWT_TEST_PAYLOAD))->getDecodedPayload()
        );

        $jwt->validateSignature();
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function testPayloadUsingCryptoConstructor()
    {
        $this->assertEquals(
            self::JWT_TEST_PAYLOAD,
            ($jwt = new JsonWebToken(self::JWT_TEST_PAYLOAD, Crypto::JWT_SYMMETRIC_HMAC_SHA256))->getDecodedPayload()
        );

        $jwt->validateSignature();
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function testPayloadUsingCryptoHmacSha384Constructor()
    {
        $this->assertTrue(
            (new JsonWebToken(self::JWT_TEST_PAYLOAD, Crypto::JWT_SYMMETRIC_HMAC_SHA384))->isValid()
        );
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function testPayloadUsingCryptoHmacSha512Constructor()
    {
        $this->assertTrue(
            (new JsonWebToken(self::JWT_TEST_PAYLOAD, Crypto::JWT_SYMMETRIC_HMAC_SHA512))->isValid()
        );
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function testPayloadUsingCryptoBlake2BConstructor()
    {
        $this->assertTrue(
            (new JsonWebToken(self::JWT_TEST_PAYLOAD, Crypto::JWT_SYMMETRIC_HMAC_BLAKE2B_HASH))->isValid()
        );
    }
}

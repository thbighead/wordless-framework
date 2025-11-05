<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\GeneratorNotSupportedException;
use Wordless\Application\Helpers\Crypto;
use Wordless\Application\Helpers\Crypto\Exceptions\DecryptionFailed;
use Wordless\Application\Helpers\Crypto\Traits\Base64\Exceptions\FailedToDecode;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Tests\WordlessTestCase;

class CryptoHelperTest extends WordlessTestCase
{
    /**
     * @param array<string, string> $dictionary
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToDecode
     */
    #[Depends('testBase64Encode')]
    public function testBase64Decode(array $dictionary): void
    {
        foreach ($dictionary as $original => $encrypted) {
            $this->assertEquals($original, Crypto::base64Decode($encrypted));
        }
    }

    /**
     * @return array<string, string>
     * @throws ExpectationFailedException
     * @throws GeneratorNotSupportedException
     */
    public function testBase64Encode(): array
    {
        $dictionary = [];
        $this->assertNotEmpty(
            $dictionary[StrHelperTest::BASE_STRING] = Crypto::base64Encode(StrHelperTest::BASE_STRING)
        );
        $this->assertEmpty($dictionary[''] = Crypto::base64Encode(''));

        return $dictionary;
    }

    /**
     * @param array $dictionary
     * @return void
     * @throws DecryptionFailed
     * @throws ExpectationFailedException
     */
    #[Depends('testEncrypt')]
    public function testDecrypt(array $dictionary): void
    {
        foreach ($dictionary as $original => $encrypted) {
            $this->assertEquals($original, Crypto::decrypt($encrypted));
        }
    }

    /**
     * @return array<string, string>
     * @throws CannotResolveEnvironmentGet
     * @throws ExpectationFailedException
     * @throws GeneratorNotSupportedException
     */
    public function testEncrypt(): array
    {
        $dictionary = [];
        $this->assertNotEmpty($dictionary[StrHelperTest::BASE_STRING] = Crypto::encrypt(StrHelperTest::BASE_STRING));
        $this->assertNotEmpty($dictionary[''] = Crypto::encrypt(''));

        return $dictionary;
    }
}

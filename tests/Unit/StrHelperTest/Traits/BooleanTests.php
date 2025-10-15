<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\Str;

trait BooleanTests
{
    private const NON_UUID = 'non_uuid_string';

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testContains(): void
    {
        $this->assertTrue(Str::contains(self::BASE_STRING, 'Test'));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['Test', 'Test']));
        $this->assertTrue(Str::contains(self::BASE_STRING, ''));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['', '']));
        $this->assertTrue(Str::contains(self::BASE_STRING, 'Test', false));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['Test', 'Test'], false));
        $this->assertTrue(Str::contains(self::BASE_STRING, '', false));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['', ''], false));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['tring', 'Test', 'k']));

        $this->assertTrue(Str::contains(self::BASE_STRING, ['tring', 'Test', 'k'], false));
        $this->assertFalse(Str::contains(self::BASE_STRING, '$'));
        $this->assertFalse(Str::contains(self::BASE_STRING, '$', false));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testBeginsWith(): void
    {
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, 'Test'));
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, self::BASE_STRING));
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, ''));

        $this->assertFalse(Str::beginsWith(self::BASE_STRING, 'ase'));
        $this->assertFalse(Str::beginsWith(self::BASE_STRING, '$'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testEndsWith(): void
    {
        $this->assertTrue(Str::endsWith(self::BASE_STRING, 'strings'));
        $this->assertTrue(Str::endsWith(self::BASE_STRING, self::BASE_STRING));
        $this->assertTrue(Str::endsWith(self::BASE_STRING, ''));

        $this->assertFalse(Str::endsWith(self::BASE_STRING, '$'));
        $this->assertFalse(Str::endsWith(self::BASE_STRING, 'tring'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsJson(): void
    {
        $this->assertTrue(Str::isJson('{}'));
        $this->assertTrue(Str::isJson('[]'));

        $this->assertFalse(Str::isJson(self::BASE_STRING));
        $this->assertFalse(Str::isJson(''));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsWrappedBy(): void
    {
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, 'Test', 'Substrings'));
        $this->assertTrue(Str::isWrappedBy(self::COUNT_STRING, 'Test'));
        $this->assertTrue(Str::isWrappedBy(self::COUNT_STRING, 'Test', 'Test'));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, ''));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, '', ''));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, '', 'Substrings'));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, 'Test', ''));

        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$', 'Substrings'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, 'Test', '$'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$', '$'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$'));
    }
}

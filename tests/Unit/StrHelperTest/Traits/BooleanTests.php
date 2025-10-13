<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use Wordless\Application\Helpers\Str;

trait BooleanTests
{
    private const NON_UUID = 'non_uuid_string';

    public function testEndsWith(): void
    {
        $this->assertTrue(Str::endsWith(self::BASE_STRING, 'Substrings'));
        $this->assertTrue(Str::endsWith(self::BASE_STRING, self::BASE_STRING));

        $this->assertFalse(Str::endsWith(self::BASE_STRING, '$'));
        $this->assertFalse(Str::endsWith(self::BASE_STRING, ''));
    }

    public function testBeginsWith(): void
    {
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, 'Test'));
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, self::BASE_STRING));

        $this->assertFalse(Str::beginsWith(self::BASE_STRING, '$'));
        $this->assertFalse(Str::beginsWith(self::BASE_STRING, ''));
    }

    public function testIsWrappedBy(): void
    {
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, 'Test', 'Substrings'));
        $this->assertTrue(Str::isWrappedBy(self::COUNT_STRING, 'Test'));
        $this->assertTrue(Str::isWrappedBy(self::COUNT_STRING, 'Test', 'Test'));

        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, ''));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '', ''));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '', 'Substrings'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, 'Test', ''));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$', 'Substrings'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, 'Test', '$'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$', '$'));
    }

    public function testStringContains(): void
    {
        $this->assertTrue(Str::contains(self::BASE_STRING, 'Test'));
        $this->assertFalse(Str::contains(self::BASE_STRING, '$'));
    }
}

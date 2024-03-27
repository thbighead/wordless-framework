<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use Wordless\Application\Helpers\Str;

trait SubstringTests
{
    public function testAfterSubstring(): void
    {
        $this->assertEquals(
            'Substrings',
            Str::after(self::BASE_STRING, 'String')
        );

        $this->assertEquals(
            '',
            Str::after(self::BASE_STRING, 'Substrings')
        );

        $this->assertEquals(
            'StringSubstrings',
            Str::after(self::BASE_STRING, 'Test')
        );
    }

    public function testBeforeSubstring(): void
    {
        $this->assertEquals(
            'Test',
            Str::before(self::BASE_STRING, 'String')
        );

        $this->assertEquals(
            'TestString',
            Str::before(self::BASE_STRING, 'Substring')
        );

        $this->assertEquals(
            '',
            Str::before(self::BASE_STRING, 'Test')
        );
    }

    public function testAfterLastSubstring(): void
    {
        $this->assertEquals(
            'strings',
            Str::afterLast(self::BASE_STRING, 'b')
        );

        $this->assertEquals(
            'estStringSubstrings',
            Str::afterLast(self::BASE_STRING, 'T')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::afterLast(self::BASE_STRING, '$')
        );
    }

    public function testBeforeLastSubstring(): void
    {
        $this->assertEquals(
            'TestStringSu',
            Str::beforeLast(self::BASE_STRING, 'b')
        );

        $this->assertEquals(
            '',
            Str::beforeLast(self::BASE_STRING, 'T')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::beforeLast(self::BASE_STRING, '$')
        );
    }

    public function testBetweenSubstring(): void
    {
        $this->assertEquals(
            'String',
            Str::between(self::BASE_STRING, 'Test', 'Substrings')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::beforeLast(self::BASE_STRING, '$')
        );
    }
}

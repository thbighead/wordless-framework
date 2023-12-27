<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use Wordless\Application\Helpers\Str;

trait SubstringTests
{
    private const BASE_STRING = 'TestStringSubstrings';

    public function testAfterSubstring(): void
    {
        $this->assertEquals(
            'Substrings',
            Str::after(self::BASE_STRING, 'String')
        );

        $this->assertEquals(
            '',
            Str::after(self::BASE_STRING, 'Substring')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::after(self::BASE_STRING, 'Test')
        );
    }

    public function testBeforeSubstring(): void
    {
        $this->assertEquals(
            'Substrings',
            Str::before(self::BASE_STRING, 'String')
        );

        $this->assertEquals(
            'TestString',
            Str::before(self::BASE_STRING, 'Substring')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::before(self::BASE_STRING, 'Test')
        );
    }
}

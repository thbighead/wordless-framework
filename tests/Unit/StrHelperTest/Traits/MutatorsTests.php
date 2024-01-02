<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait MutatorsTests
{
    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testUnaccented(): void
    {
        $this->assertEquals(
            self::BASE_STRING,
            Str::unaccented(self::BASE_STRING)
        );

        $this->assertEquals(
            'Euoa',
            Str::unaccented('Éûõà')
        );
    }

    public function testForceFinishWith(): void
    {
        $this->assertEquals(
            self::BASE_STRING,
            Str::before(self::BASE_STRING, 'Substrings')
        );

        $this->assertEquals(
            self::BASE_STRING . 'Test',
            Str::before(self::BASE_STRING, 'Test')
        );
    }

    public function testRemoveSuffix(): void
    {
        $this->assertEquals(
            'StringSubstrings',
            Str::removeSuffix(self::BASE_STRING, 'Test')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::removeSuffix(self::BASE_STRING, '$')
        );
    }

    public function testReplace(): void
    {
        $this->assertEquals(
            '$StringSubstrings',
            Str::replace(self::BASE_STRING, 'Test', '$')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::replace(self::BASE_STRING, '$', '$')
        );
    }

    public function testForceStartWith(): void
    {
        $this->assertEquals(
            self::BASE_STRING,
            Str::startWith(self::BASE_STRING, 'Test')
        );

        $this->assertEquals(
            '$' . self::BASE_STRING,
            Str::startWith(self::BASE_STRING, '$')
        );
    }
}

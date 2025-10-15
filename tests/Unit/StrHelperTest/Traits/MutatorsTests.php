<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use TypeError;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;

trait MutatorsTests
{
    /**
     * @return void
     * @throws FailedToCreateInflector
     * @throws ExpectationFailedException
     */
    public function testUnaccented(): void
    {
        $this->assertEquals(
            self::BASE_STRING,
            Str::unaccented(self::BASE_STRING)
        );

        $this->assertEquals(
            'Euoa!!',
            Str::unaccented('Éûõà!!')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testFinishWith(): void
    {
        $this->assertEquals(
            self::BASE_STRING,
            Str::finishWith(self::BASE_STRING, 'Substrings')
        );
        $this->assertEquals(
            self::BASE_STRING,
            Str::finishWith(self::BASE_STRING, '')
        );

        $this->assertEquals(
            self::BASE_STRING . 'Test',
            Str::finishWith(self::BASE_STRING, 'Test')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRemoveSuffix(): void
    {
        $this->assertEquals(
            'TestString',
            Str::removeSuffix(self::BASE_STRING, 'Substrings')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::removeSuffix(self::BASE_STRING, '$')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::removeSuffix(self::BASE_STRING, '')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testReplace(): void
    {
        $this->assertEquals(
            '$StringSubstrings',
            Str::replace(self::BASE_STRING, 'Test', '$')
        );
        $this->assertEquals(
            'a caçada hegemônica é responsável pela destruição!',
            Str::replace(self::ACCENTED_RAW_CASE_EXAMPLE, ['não ', '?'], '')
        );
        $this->assertEquals(
            'a caçada hegemônica sempre é responsável pela destruição?!',
            Str::replace(self::ACCENTED_RAW_CASE_EXAMPLE, ['não', '?'], ['sempre'])
        );
        $this->assertEquals(
            'a caçada hegemônica sempre é responsável pela destruição?!',
            Str::replace(self::ACCENTED_RAW_CASE_EXAMPLE, ['não'], ['sempre', '!'])
        );
        $this->assertEquals(
            'a caçada hegemônica sempre é responsável pela destruição!!',
            Str::replace(self::ACCENTED_RAW_CASE_EXAMPLE, ['não', '?'], ['sempre', '!'])
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::replace(self::BASE_STRING, '$', 'aaa')
        );
        $this->assertEquals(
            self::BASE_STRING,
            Str::replace(self::BASE_STRING, 'Test', 'Test')
        );

        $this->expectException(TypeError::class);
        Str::replace(self::ACCENTED_RAW_CASE_EXAMPLE, 'ão', ['em', 'ion']);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testStartWith(): void
    {
        $this->assertEquals(
            self::BASE_STRING,
            Str::startWith(self::BASE_STRING, 'Test')
        );
        $this->assertEquals(
            self::BASE_STRING,
            Str::startWith(self::BASE_STRING, '')
        );

        $this->assertEquals(
            '$' . self::BASE_STRING,
            Str::startWith(self::BASE_STRING, '$')
        );
    }
}

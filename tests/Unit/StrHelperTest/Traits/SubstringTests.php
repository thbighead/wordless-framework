<?php declare(strict_types=1);

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\Str;

trait SubstringTests
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
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
            'Substrings',
            Str::after(self::BASE_STRING, 'tring')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::after(self::BASE_STRING, '')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::after(self::BASE_STRING, '$')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testAfterLastSubstring(): void
    {
        $this->assertEquals(
            's',
            Str::afterLast(self::BASE_STRING, 'tring')
        );

        $this->assertEquals(
            'rings',
            Str::afterLast(self::BASE_STRING, 't')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::afterLast(self::BASE_STRING, '$')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::afterLast(self::BASE_STRING, '')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testBeforeSubstring(): void
    {
        $this->assertEquals(
            'TestS',
            Str::before(self::BASE_STRING, 'tring')
        );

        $this->assertEquals(
            'TestString',
            Str::before(self::BASE_STRING, 'Substring')
        );

        $this->assertEquals(
            '',
            Str::before(self::BASE_STRING, 'Test')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::before(self::BASE_STRING, '')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::before(self::BASE_STRING, '$')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testBeforeLastSubstring(): void
    {
        $this->assertEquals(
            'TestStringSubs',
            Str::beforeLast(self::BASE_STRING, 'tring')
        );

        $this->assertEquals(
            '',
            Str::beforeLast(self::BASE_STRING, 'Test')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::beforeLast(self::BASE_STRING, '$')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::beforeLast(self::BASE_STRING, '')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testBetweenSubstring(): void
    {
        $this->assertEquals(
            'StringSubs',
            Str::between(self::BASE_STRING, 'Test', 'tring')
        );

        $this->assertEquals(
            'SubstringsS',
            Str::between(self::BASE_STRING . 'Strings', 'tring')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::between(self::BASE_STRING, '$')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::between(self::BASE_STRING, '')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::between(self::BASE_STRING, 'Test', '')
        );

        $this->assertEquals(
            self::BASE_STRING,
            Str::between(self::BASE_STRING, 'Test', '$')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testCountSubstring(): void
    {
        $this->assertEquals(1, Str::countSubstring(self::BASE_STRING, self::BASE_STRING));
        $this->assertEquals(1, Str::countSubstring(self::BASE_STRING, 'Test'));
        $this->assertEquals(2, Str::countSubstring(self::BASE_STRING, 'tring'));
        $this->assertEquals(0, Str::countSubstring(self::BASE_STRING, '$'));
        $this->assertEquals(0, Str::countSubstring(self::BASE_STRING, ''));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testLimitWords(): void
    {
        $this->assertEquals('Test...', Str::limitWords(self::COUNT_STRING, 1));
        $this->assertEquals('Test Test...', Str::limitWords(self::COUNT_STRING, 2));
        $this->assertEquals('Test Test Test...', Str::limitWords(self::COUNT_STRING, 3));
        $this->assertEquals(self::COUNT_STRING, Str::limitWords(self::COUNT_STRING, 4));
        $this->assertEquals(self::COUNT_STRING, Str::limitWords(self::COUNT_STRING, 5));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testTruncate(): void
    {
        $this->assertEquals(
            Str::truncate(self::BASE_STRING . self::BASE_STRING . self::BASE_STRING),
            Str::truncate(self::BASE_STRING . self::BASE_STRING . self::BASE_STRING, 0)
        );
        $this->assertEquals(
            Str::truncate(self::BASE_STRING),
            Str::truncate(self::BASE_STRING, -50)
        );
        $this->assertEquals(
            Str::truncate(self::BASE_STRING),
            Str::truncate(self::BASE_STRING, -Str::length(self::BASE_STRING))
        );
        $this->assertEquals('TestS', Str::truncate(self::BASE_STRING, 5));
        $this->assertEquals('TestStringSubstring', Str::truncate(self::BASE_STRING, -1));
    }
}

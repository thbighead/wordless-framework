<?php

namespace Wordless\Tests\Unit;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\Unit\StrHelperTest\Traits\BooleanTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\SubstringTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\UuidTests;
use Wordless\Tests\WordlessTestCase;

class StrHelperTest extends WordlessTestCase
{
    use BooleanTests;
    use CaseStyleTests;
    use MutatorsTests;
    use UuidTests;
    use SubstringTests;

    private const BASE_STRING = 'TestStringSubstrings';
    private const COUNT_STRING = 'Test Test Test Test';

    public function testCountSubstring(): void
    {
        $this->assertEquals(1, Str::countSubstring(self::BASE_STRING, 'Test'));
        $this->assertEquals(2, Str::countSubstring(self::BASE_STRING, 'tring'));
        $this->assertEquals(0, Str::countSubstring(self::BASE_STRING, '$'));
    }

    public function testLimitWords(): void
    {
        $this->assertEquals('Test...', Str::limitWords(self::COUNT_STRING, 1));
        $this->assertEquals('Test Test...', Str::limitWords(self::COUNT_STRING, 2));
        $this->assertEquals('Test Test Test...', Str::limitWords(self::COUNT_STRING, 3));
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testPluralize(): void
    {
        $this->assertEquals('tests', Str::plural('test'));
        $this->assertEquals('testes', Str::plural('teste', 'portuguese'));
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testSingularize(): void
    {
        $this->assertEquals('test', Str::singular('tests'));
        $this->assertEquals('teste', Str::singular('testes', 'portuguese'));
    }

    public function testRandomString()
    {
        $this->assertIsString(Str::random());
    }

    public function testTruncate()
    {
        $this->assertEquals('TestStringSubst', Str::truncate(self::BASE_STRING, 0));
        $this->assertEquals('TestS', Str::truncate(self::BASE_STRING, 5));
        $this->assertEquals('TestStringSubst', Str::truncate(self::BASE_STRING, -1));
    }
}

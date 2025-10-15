<?php

namespace Wordless\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use Random\RandomException;
use TypeError;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\Language;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
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
     * @throws FailedToCreateInflector
     */
    public function testPluralize(): void
    {
        $this->assertEquals('men', Str::plural('man'));
        $this->assertEquals('irises', Str::plural('iris'));
        $this->assertEquals('analyses', Str::plural('analysis'));
        $this->assertEquals('children', Str::plural('child'));
        $this->assertEquals('tests', Str::plural('test'));
        $this->assertEquals('energies', Str::plural('energy'));
        $this->assertEquals('rays', Str::plural('ray'));
        $this->assertEquals('testes', Str::plural('teste', Language::portuguese));
        $this->assertEquals('estações', Str::plural('estação', Language::portuguese));
        $this->assertEquals('óculos', Str::plural('óculos', Language::portuguese));
        $this->assertEquals('quais', Str::plural('qual', Language::portuguese));
        $this->assertEquals('comuns', Str::plural('comum', Language::portuguese));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     */
    public function testSingularize(): void
    {
        $this->assertEquals('man', Str::singular('men'));
        $this->assertEquals('iris', Str::singular('irises'));
        $this->assertEquals('analysis', Str::singular('analyses'));
        $this->assertEquals('child', Str::singular('children'));
        $this->assertEquals('test', Str::singular('tests'));
        $this->assertEquals('energy', Str::singular('energies'));
        $this->assertEquals('ray', Str::singular('rays'));
        $this->assertEquals('teste', Str::singular('testes', Language::portuguese));
        $this->assertEquals('estação', Str::singular('estações', Language::portuguese));
        $this->assertEquals('óculos', Str::singular('óculos', Language::portuguese));
        $this->assertEquals('qual', Str::singular('quais', Language::portuguese));
        $this->assertEquals('comum', Str::singular('comuns', Language::portuguese));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RandomException
     */
    public function testRandomString()
    {
        $this->assertEquals(Str::DEFAULT_RANDOM_SIZE, Str::length(Str::random()));
        $this->assertEquals($size = 20, Str::length(Str::random($size)));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testTruncate()
    {
        $this->assertEquals(
            Str::truncate(self::BASE_STRING),
            Str::truncate(self::BASE_STRING, 0)
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

<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests;
use TypeError;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\Language;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;

trait MutatorsTests
{
    use CaseStyleTests;

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
}

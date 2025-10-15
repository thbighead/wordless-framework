<?php

namespace Wordless\Tests\Unit;

use JsonException;
use PHPUnit\Framework\ExpectationFailedException;
use Random\RandomException;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\Unit\StrHelperTest\Traits\BooleanTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\SubstringTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\UuidTests;
use Wordless\Tests\WordlessTestCase;

class StrHelperTest extends WordlessTestCase
{
    use BooleanTests;
    use MutatorsTests;
    use UuidTests;
    use SubstringTests;

    private const BASE_STRING = 'TestStringSubstrings';
    private const COUNT_STRING = 'Test Test Test Test';

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws JsonException
     */
    public function testJsonDecode(): void
    {
        $this->assertEquals([], Str::jsonDecode('[]'));
        $this->assertEquals([], Str::jsonDecode('{}'));
        $this->assertEquals([
            'test' => 'yeah',
            'bool' => true,
            'number' => 123,
            'maybe_null' => null,
            'list' => [
                true,
                false,
                null,
                45,
                'told_ya',
                ['big_test' => 'ok', 'or' => 'Not'],
                [1, 2, 3, 4],
            ],
            'sub_object' => ['what' => 'is', 'done' => 80],
        ], Str::jsonDecode('{"test":"yeah","bool":true,"number":123,"maybe_null":null,"list":[true,false,null,45,"told_ya",{"big_test": "ok","or":"Not"},[1,2,3,4]],"sub_object":{"what":"is","done":"is","done":80}}'));

        $this->expectException(JsonException::class);
        Str::jsonDecode('');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RandomException
     */
    public function testRandomString(): void
    {
        $this->assertEquals(Str::DEFAULT_RANDOM_SIZE, Str::length(Str::random()));
        $this->assertEquals($size = 20, Str::length(Str::random($size)));
    }
}

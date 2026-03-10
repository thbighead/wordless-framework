<?php declare(strict_types=1);

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\Unit\StrHelperTest;

/**
 * @mixin StrHelperTest
 */
trait BooleanTests
{
    private const NON_UUID = 'non_uuid_string';

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testBeginsWith(): void
    {
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, 'Test'));
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, self::BASE_STRING));
        $this->assertTrue(Str::beginsWith(self::BASE_STRING, ''));

        $this->assertFalse(Str::beginsWith(self::BASE_STRING, 'ase'));
        $this->assertFalse(Str::beginsWith(self::BASE_STRING, '$'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testContains(): void
    {
        $this->assertTrue(Str::contains(self::BASE_STRING, 'Test'));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['Test', 'Test']));
        $this->assertTrue(Str::contains(self::BASE_STRING, 'Test', false));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['Test', 'Test'], false));
        $this->assertTrue(Str::contains(self::BASE_STRING, ['tring', 'Test', 'k']));

        $this->assertFalse(Str::contains(self::BASE_STRING, ['tring', 'Test', 'k'], false));
        $this->assertFalse(Str::contains(self::BASE_STRING, '$'));
        $this->assertFalse(Str::contains(self::BASE_STRING, ''));
        $this->assertFalse(Str::contains(self::BASE_STRING, ['', '']));
        $this->assertFalse(Str::contains(self::BASE_STRING, '', false));
        $this->assertFalse(Str::contains(self::BASE_STRING, ['', ''], false));
        $this->assertFalse(Str::contains(self::BASE_STRING, '$', false));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testEndsWith(): void
    {
        $this->assertTrue(Str::endsWith(self::BASE_STRING, 'strings'));
        $this->assertTrue(Str::endsWith(self::BASE_STRING, self::BASE_STRING));
        $this->assertTrue(Str::endsWith(self::BASE_STRING, ''));

        $this->assertFalse(Str::endsWith(self::BASE_STRING, '$'));
        $this->assertFalse(Str::endsWith(self::BASE_STRING, 'tring'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsEmpty(): void
    {
        $this->assertTrue(Str::isEmpty(''));

        $this->assertFalse(Str::isEmpty(self::BASE_STRING));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws PathNotFoundException
     */
    public function testIsJson(): void
    {
        $this->assertTrue(Str::isJson('{}'));
        $this->assertTrue(Str::isJson('[]'));
        $this->assertTrue(Str::isJson(
            '{"test":"yeah","bool":true,"number":123,"maybe_null":null,"list":[true,false,null,45,"told_ya",{"big_test": "ok","or":"Not"},[1,2,3,4]],"sub_object":{"what":"is","done":"is","done":80}}'
        ));
        $this->assertTrue(Str::isJson(ProjectPath::root('composer.json')));

        $this->assertFalse(Str::isJson(ProjectPath::root('.gitignore')));
        $this->assertFalse(Str::isJson(self::BASE_STRING));
        $this->assertFalse(Str::isJson(''));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsWrappedBy(): void
    {
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, 'Test', 'Substrings'));
        $this->assertTrue(Str::isWrappedBy(self::COUNT_STRING, 'Test'));
        $this->assertTrue(Str::isWrappedBy(self::COUNT_STRING, 'Test', 'Test'));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, ''));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, '', ''));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, '', 'Substrings'));
        $this->assertTrue(Str::isWrappedBy(self::BASE_STRING, 'Test', ''));

        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$', 'Substrings'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, 'Test', '$'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$', '$'));
        $this->assertFalse(Str::isWrappedBy(self::BASE_STRING, '$'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsUuid(): void
    {
        $this->assertFalse(Str::isUuid(self::BASE_STRING));
    }
}

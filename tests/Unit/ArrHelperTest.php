<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use JsonException;
use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Application\Helpers\Arr\Exceptions\ArrayKeyAlreadySet;
use Wordless\Application\Helpers\Arr\Exceptions\EmptyArrayHasNoIndex;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\WordlessTestCase;
use Wordless\Tests\WordlessTestCase\Traits\SubjectDtoHelperTests;

class ArrHelperTest extends WordlessTestCase
{
    use SubjectDtoHelperTests;

    public const JSON_ARRAY = [
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
            self::ASSOCIATIVE_ARRAY,
            self::LIST_ARRAY,
        ],
        'sub_object' => ['what' => 'is', 'done' => 80],
    ];
    private const ASSOCIATIVE_ARRAY = ['big_test' => 'ok', 'or' => 'Not'];
    private const LIST_ARRAY = [1, 2, 3, 4];

    /**
     * @return void
     * @throws ArrayKeyAlreadySet
     * @throws ExpectationFailedException
     */
    public function testAppend(): void
    {
        $key1 = 'test';
        $key2 = 'big_test';
        $value = 5;

        $this->assertEquals([$value], Arr::append([], $value));
        $this->assertEquals([4 => $value], Arr::append([], $value, 4));
        $this->assertEquals(['' => $value], Arr::append([], $value, ''));
        $this->assertEquals([1, 2, 3, 4, $value], Arr::append(self::LIST_ARRAY, $value));
        $this->assertEquals([1, 2, 3, 4, $value], Arr::append(self::LIST_ARRAY, $value, 4));
        $this->assertEquals([1, 2, 3, 4, $key1 => $value], Arr::append(self::LIST_ARRAY, $value, $key1));
        $this->assertEquals([1, 2, 3, 4, '' => $value], Arr::append(self::LIST_ARRAY, $value, ''));

        $this->assertEquals(
            ['big_test' => 'ok', 'or' => 'Not', $value],
            Arr::append(self::ASSOCIATIVE_ARRAY, $value)
        );
        $this->assertEquals(
            ['big_test' => 'ok', 'or' => 'Not', $value],
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, 0)
        );
        $this->assertEquals(
            ['big_test' => 'ok', 'or' => 'Not', $key1 => $value],
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, $key1)
        );
        $this->assertEquals(
            ['big_test' => 'ok', 'or' => 'Not', '' => $value],
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, '')
        );

        $this->expectException(ArrayKeyAlreadySet::class);
        Arr::append(self::ASSOCIATIVE_ARRAY, $value, $key2);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExcept(): void
    {
        $this->assertEquals([], Arr::except([], 0, 1));
        $this->assertEquals([], Arr::except([], 2));
        $this->assertEquals([], Arr::except([], 7, 'test'));
        $this->assertEquals([], Arr::except([], 'or'));
        $this->assertEquals([], Arr::except([], ''));
        $this->assertEquals([], Arr::except([]));

        $this->assertEquals([2 => 3, 3 => 4], Arr::except(self::LIST_ARRAY, 0, 1));
        $this->assertEquals([1, 2, 3 => 4], Arr::except(self::LIST_ARRAY, 2, 77));
        $this->assertEquals([1, 2, 3 => 4], Arr::except(self::LIST_ARRAY, 2));
        $this->assertEquals(self::LIST_ARRAY, Arr::except(self::LIST_ARRAY, 7));
        $this->assertEquals(self::LIST_ARRAY, Arr::except(self::LIST_ARRAY, 7, 'test'));
        $this->assertEquals(self::LIST_ARRAY, Arr::except(self::LIST_ARRAY, ''));
        $this->assertEquals(self::LIST_ARRAY, Arr::except(self::LIST_ARRAY));

        $this->assertEquals(
            [],
            Arr::except(self::ASSOCIATIVE_ARRAY, ...array_keys(self::ASSOCIATIVE_ARRAY))
        );
        $this->assertEquals(['big_test' => 'ok'], Arr::except(self::ASSOCIATIVE_ARRAY, 'or'));
        $this->assertEquals(
            ['big_test' => 'ok'],
            Arr::except(self::ASSOCIATIVE_ARRAY, 'or', 'hhhh')
        );
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::except(self::ASSOCIATIVE_ARRAY, 7)
        );
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::except(self::ASSOCIATIVE_ARRAY, 7, 'test')
        );
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::except(self::ASSOCIATIVE_ARRAY, '', '')
        );
        $this->assertEquals(self::ASSOCIATIVE_ARRAY, Arr::except(self::ASSOCIATIVE_ARRAY));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testFirst(): void
    {
        $this->assertNull(Arr::first([]));
        $this->assertNull(Arr::first([], 5));
        $this->assertNull(Arr::first([], -3));
        $this->assertNull(Arr::first([], 0));

        $this->assertEquals(1, Arr::first(self::LIST_ARRAY));
        $this->assertEquals(1, Arr::first(self::LIST_ARRAY, -3));
        $this->assertEquals(1, Arr::first(self::LIST_ARRAY, 0));
        $this->assertEquals(
            self::LIST_ARRAY,
            Arr::first(self::LIST_ARRAY, count(self::LIST_ARRAY))
        );
        $this->assertEquals([1, 2], Arr::first(self::LIST_ARRAY, 2));

        $this->assertEquals('ok', Arr::first(self::ASSOCIATIVE_ARRAY));
        $this->assertEquals('ok', Arr::first(self::ASSOCIATIVE_ARRAY, -3));
        $this->assertEquals('ok', Arr::first(self::ASSOCIATIVE_ARRAY, 0));
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::first(self::ASSOCIATIVE_ARRAY, count(self::ASSOCIATIVE_ARRAY))
        );
        $this->assertEquals(
            ['test' => 'yeah', 'bool' => true, 'number' => 123],
            Arr::first(self::JSON_ARRAY, 3)
        );
    }

    /**
     * @return void
     * @throws FailedToParseArrayKey
     * @throws ExpectationFailedException
     */
    public function testGet(): void
    {
        $default = '34';

        $this->assertNull(Arr::get([], -1));
        $this->assertNull(Arr::get([], 0));
        $this->assertNull(Arr::get([], 4));
        $this->assertNull(Arr::get([], 'ggg'));
        $this->assertNull(Arr::get([], ''));
        $this->assertEquals($default, Arr::get([], -1, $default));
        $this->assertEquals($default, Arr::get([], 0, $default));
        $this->assertEquals($default, Arr::get([], 4, $default));
        $this->assertEquals($default, Arr::get([], 'ggg', $default));
        $this->assertEquals($default, Arr::get([], '', $default));

        $this->assertEquals(1, Arr::get(self::LIST_ARRAY, 0));
        $this->assertEquals(1, Arr::get(self::LIST_ARRAY, 0, $default));
        $this->assertEquals(3, Arr::get(self::LIST_ARRAY, 2));
        $this->assertEquals(3, Arr::get(self::LIST_ARRAY, 2, $default));
        $this->assertNull(Arr::get(self::LIST_ARRAY, -1));
        $this->assertNull(Arr::get(self::LIST_ARRAY, ''));
        $this->assertEquals($default, Arr::get(self::LIST_ARRAY, -1, $default));
        $this->assertEquals($default, Arr::get(self::LIST_ARRAY, '', $default));

        $this->assertEquals('Not', Arr::get(self::ASSOCIATIVE_ARRAY, 'or'));
        $this->assertEquals('Not', Arr::get(self::ASSOCIATIVE_ARRAY, 'or', $default));
        $this->assertNull(Arr::get(self::ASSOCIATIVE_ARRAY, 2));
        $this->assertNull(Arr::get(self::ASSOCIATIVE_ARRAY, ''));
        $this->assertEquals($default, Arr::get(self::ASSOCIATIVE_ARRAY, -1, $default));
        $this->assertEquals($default, Arr::get(self::ASSOCIATIVE_ARRAY, '', $default));

        $this->assertEquals(45, Arr::get(self::JSON_ARRAY, 'list.3'));
        $this->assertEquals(45, Arr::get(self::JSON_ARRAY, 'list.3', $default));
        $this->assertEquals('is', Arr::get(self::JSON_ARRAY, 'sub_object.what'));
        $this->assertEquals('is', Arr::get(self::JSON_ARRAY, 'sub_object.what', $default));
        $this->assertNull(Arr::get(self::JSON_ARRAY, 'sub_object.invalid'));
        $this->assertEquals($default, Arr::get(self::JSON_ARRAY, 'sub_object.invalid', $default));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testGetFirstKey(): void
    {
        $this->assertNull(Arr::getFirstKey([]));
        $this->assertEquals('big_test', Arr::getFirstKey(self::ASSOCIATIVE_ARRAY));
        $this->assertEquals(0, Arr::getFirstKey(self::LIST_ARRAY));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testGetIndexOfKey(): void
    {
        $this->assertNull(Arr::getIndexOfKey([], 1));
        $this->assertNull(Arr::getIndexOfKey([], 'test'));
        $this->assertNull(Arr::getIndexOfKey([], ''));
        $this->assertEquals(0, Arr::getIndexOfKey(self::ASSOCIATIVE_ARRAY, 'big_test'));
        $this->assertNull(Arr::getIndexOfKey(self::ASSOCIATIVE_ARRAY, 'aaaa'));
        $this->assertNull(Arr::getIndexOfKey(self::ASSOCIATIVE_ARRAY, ''));
        $this->assertEquals(3, Arr::getIndexOfKey(self::LIST_ARRAY, 3));
        $this->assertNull(Arr::getIndexOfKey(self::LIST_ARRAY, 'ffff'));
        $this->assertNull(Arr::getIndexOfKey(self::LIST_ARRAY, ''));
        $this->assertEquals(2, Arr::getIndexOfKey(self::JSON_ARRAY, 'number'));
        $this->assertEquals(1, Arr::getIndexOfKey(['u', '' => 'v'], ''));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToFindArrayKey
     * @throws FailedToParseArrayKey
     */
    public function testGetOrFail(): void
    {
        $this->assertEquals(1, Arr::getOrFail(self::LIST_ARRAY, 0));
        $this->assertEquals(3, Arr::getOrFail(self::LIST_ARRAY, 2));

        $this->assertEquals('Not', Arr::getOrFail(self::ASSOCIATIVE_ARRAY, 'or'));

        $this->assertEquals(45, Arr::getOrFail(self::JSON_ARRAY, 'list.3'));
        $this->assertEquals('is', Arr::getOrFail(self::JSON_ARRAY, 'sub_object.what'));

        $this->expectException(FailedToFindArrayKey::class);
        Arr::getOrFail([], '');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testHasAnyOtherValueThan(): void
    {
        $this->assertTrue(Arr::hasAnyOtherValueThan(self::LIST_ARRAY, 78));
        $this->assertTrue(Arr::hasAnyOtherValueThan(self::LIST_ARRAY, 2));
        $this->assertTrue(Arr::hasAnyOtherValueThan(self::LIST_ARRAY, 'ty'));
        $this->assertTrue(Arr::hasAnyOtherValueThan(self::ASSOCIATIVE_ARRAY, 2));
        $this->assertTrue(Arr::hasAnyOtherValueThan(self::ASSOCIATIVE_ARRAY, 'ty'));
        $this->assertTrue(Arr::hasAnyOtherValueThan(self::ASSOCIATIVE_ARRAY, 'or'));

        $this->assertFalse(Arr::hasAnyOtherValueThan([2, 2], 2));
        $this->assertFalse(Arr::hasAnyOtherValueThan(['ty', 'ty'], 'ty'));
        $this->assertFalse(Arr::hasAnyOtherValueThan(['u' => 2, 'o' => 2], 2));
        $this->assertFalse(Arr::hasAnyOtherValueThan(['a' => 'ty', 'b' => 'ty', 'ty'], 'ty'));
        $this->assertFalse(Arr::hasAnyOtherValueThan([], 2));
        $this->assertFalse(Arr::hasAnyOtherValueThan([], 'ty'));
        $this->assertFalse(Arr::hasAnyOtherValueThan([2], 2));
        $this->assertFalse(Arr::hasAnyOtherValueThan(['ty'], 'ty'));
        $this->assertFalse(Arr::hasAnyOtherValueThan(['u' => 2], 2));
        $this->assertFalse(Arr::hasAnyOtherValueThan(['a' => 'ty'], 'ty'));
        $this->assertFalse(Arr::hasAnyOtherValueThan([], 2));
        $this->assertFalse(Arr::hasAnyOtherValueThan([], 'ty'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testHasKey(): void
    {
        $this->assertTrue(Arr::hasKey(self::LIST_ARRAY, 1));
        $this->assertTrue(Arr::hasKey(self::ASSOCIATIVE_ARRAY, 'or'));
        $this->assertTrue(Arr::hasKey(['' => 6], ''));

        $this->assertFalse(Arr::hasKey(self::LIST_ARRAY, 'lalala'));
        $this->assertFalse(Arr::hasKey(self::LIST_ARRAY, ''));
        $this->assertFalse(Arr::hasKey(self::ASSOCIATIVE_ARRAY, 'lalala'));
        $this->assertFalse(Arr::hasKey(self::ASSOCIATIVE_ARRAY, ''));
        $this->assertFalse(Arr::hasKey([], 0));
        $this->assertFalse(Arr::hasKey([], 'lalala'));
        $this->assertFalse(Arr::hasKey([], ''));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testHasValue(): void
    {
        $this->assertTrue(Arr::hasValue(self::LIST_ARRAY, 2));
        $this->assertTrue(Arr::hasValue(self::ASSOCIATIVE_ARRAY, 'ok'));

        $this->assertFalse(Arr::hasValue(self::LIST_ARRAY, 78));
        $this->assertFalse(Arr::hasValue(self::LIST_ARRAY, 'ty'));
        $this->assertFalse(Arr::hasValue(self::ASSOCIATIVE_ARRAY, 2));
        $this->assertFalse(Arr::hasValue(self::ASSOCIATIVE_ARRAY, 'ty'));
        $this->assertFalse(Arr::hasValue([], 2));
        $this->assertFalse(Arr::hasValue([], 'ty'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsAssociative(): void
    {
        $this->assertTrue(Arr::isAssociative(self::ASSOCIATIVE_ARRAY));
        $this->assertTrue(Arr::isAssociative([]));

        $this->assertFalse(Arr::isAssociative(self::LIST_ARRAY));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testIsEmpty(): void
    {
        $this->assertTrue(Arr::isEmpty([]));

        $this->assertFalse(Arr::isEmpty(self::ASSOCIATIVE_ARRAY));
        $this->assertFalse(Arr::isEmpty(self::LIST_ARRAY));
    }

    /**
     * @return void
     * @throws EmptyArrayHasNoIndex
     * @throws ExpectationFailedException
     */
    public function testLastIndex(): void
    {
        $this->assertEquals(1, Arr::lastIndex(self::ASSOCIATIVE_ARRAY));
        $this->assertEquals(3, Arr::lastIndex(self::LIST_ARRAY));

        $this->expectException(EmptyArrayHasNoIndex::class);
        Arr::lastIndex([]);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToParseArrayKey
     */
    public function testOnly(): void
    {
        $this->assertEquals([], Arr::only([]));
        $this->assertEquals([], Arr::only([], ''));
        $this->assertEquals([], Arr::only([], ...self::LIST_ARRAY));
        $this->assertEquals([], Arr::only(self::LIST_ARRAY));
        $this->assertEquals([], Arr::only([], ...self::ASSOCIATIVE_ARRAY));
        $this->assertEquals([], Arr::only(self::ASSOCIATIVE_ARRAY));
        $this->assertEquals([], Arr::only(self::LIST_ARRAY, 'nothing'));
        $this->assertEquals([], Arr::only(self::LIST_ARRAY, '', '', 'nothing'));
        $this->assertEquals([], Arr::only(self::ASSOCIATIVE_ARRAY, 'nothing'));
        $this->assertEquals([], Arr::only(self::ASSOCIATIVE_ARRAY, ''));

        $this->assertEquals([0 => 1, 2 => 3], Arr::only(self::LIST_ARRAY, 0, 2));
        $this->assertEquals([0 => 1, 2 => 3], Arr::only(self::LIST_ARRAY, 0, 0, 2));
        $this->assertEquals([2 => 3], Arr::only(self::LIST_ARRAY, 2, 2));
        $this->assertEquals(['big_test' => 'ok'], Arr::only(self::ASSOCIATIVE_ARRAY, 'big_test'));
        $this->assertEquals(
            ['big_test' => 'ok'],
            Arr::only(self::ASSOCIATIVE_ARRAY, 'big_test', 'big_test')
        );
        $this->assertEquals(
            ['big_test' => 'ok'],
            Arr::only(self::ASSOCIATIVE_ARRAY, 'big_test', 8, 9)
        );
        $this->assertEquals(
            [
                'number' => 123,
                'list' => [5 => ['big_test' => 'ok']],
                'sub_object' => ['what' => 'is', 'done' => 80],
            ],
            Arr::only(self::JSON_ARRAY, 'number', 'list.5.big_test', 'sub_object')
        );
        $this->assertEquals(
            [
                'number' => 123,
                'sub_object' => ['what' => 'is', 'done' => 80],
            ],
            Arr::only(self::JSON_ARRAY, 'number', 'list.5.', 'sub_object')
        );

        $this->assertEquals(
            self::LIST_ARRAY,
            Arr::only(self::LIST_ARRAY, ...array_keys(self::LIST_ARRAY))
        );
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::only(self::ASSOCIATIVE_ARRAY, ...array_keys(self::ASSOCIATIVE_ARRAY))
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPackBy(): void
    {
        $this->assertEquals([], Arr::packBy([], -3));
        $this->assertEquals([], Arr::packBy([], 0));
        $this->assertEquals([], Arr::packBy([], 2));

        $this->assertEquals([['r' => 't']], Arr::packBy(['r' => 't'], -3));
        $this->assertEquals([['r' => 't']], Arr::packBy(['r' => 't'], 0));
        $this->assertEquals([['r' => 't']], Arr::packBy(['r' => 't'], 2));
        $this->assertEquals([['r' => 't']], Arr::packBy(['r' => 't'], 1));

        $this->assertEquals(
            [self::LIST_ARRAY],
            Arr::packBy(self::LIST_ARRAY, count(self::LIST_ARRAY))
        );
        $this->assertEquals(
            [[1], [2], [3], [4]],
            Arr::packBy(self::LIST_ARRAY, 0)
        );
        $this->assertEquals(
            [[1, 2, 3], [4]],
            Arr::packBy(self::LIST_ARRAY, 3)
        );
        $this->assertEquals(
            [['big_test' => 'ok'], ['or' => 'Not']],
            Arr::packBy(self::ASSOCIATIVE_ARRAY, 0)
        );
        $this->assertEquals(
            [self::ASSOCIATIVE_ARRAY],
            Arr::packBy(self::ASSOCIATIVE_ARRAY, count(self::ASSOCIATIVE_ARRAY))
        );
        $this->assertEquals(
            [
                ['test' => 'yeah', 'bool' => true],
                ['number' => 123, 'maybe_null' => null],
                ['list' => [
                    true,
                    false,
                    null,
                    45,
                    'told_ya',
                    self::ASSOCIATIVE_ARRAY,
                    self::LIST_ARRAY,
                ], 'sub_object' => ['what' => 'is', 'done' => 80]],
            ],
            Arr::packBy(self::JSON_ARRAY, 2)
        );
    }

    /**
     * @return void
     * @throws ArrayKeyAlreadySet
     * @throws ExpectationFailedException
     */
    public function testPrepend(): void
    {
        $key1 = 'test';
        $key2 = 'big_test';
        $value = 5;

        $this->assertEquals([$value], Arr::prepend([], $value));
        $this->assertEquals([4 => $value], Arr::prepend([], $value, 4));
        $this->assertEquals([$value, 1, 2, 3, 4], Arr::prepend(self::LIST_ARRAY, $value));
        $this->assertEquals([$value, 1, 2, 3, 4], Arr::prepend(self::LIST_ARRAY, $value, 0));
        $this->assertEquals([$key1 => $value, 1, 2, 3, 4], Arr::prepend(self::LIST_ARRAY, $value, $key1));
        $this->assertEquals(['' => $value, 1, 2, 3, 4], Arr::prepend(self::LIST_ARRAY, $value, ''));

        $this->assertEquals(
            [$value, 'big_test' => 'ok', 'or' => 'Not'],
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value)
        );
        $this->assertEquals(
            [$value, 'big_test' => 'ok', 'or' => 'Not'],
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value, 0)
        );
        $this->assertEquals(
            [$key1 => $value, 'big_test' => 'ok', 'or' => 'Not'],
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value, $key1)
        );
        $this->assertEquals(
            ['' => $value, 'big_test' => 'ok', 'or' => 'Not'],
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value, '')
        );

        $this->expectException(ArrayKeyAlreadySet::class);
        Arr::prepend(self::ASSOCIATIVE_ARRAY, $value, $key2);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testPrint(): void
    {
        $this->assertEquals(
            "array (\n)",
            Arr::print([])
        );
        $this->assertEquals(
            "array (\n  0 => 1,\n  1 => 2,\n  2 => 3,\n  3 => 4,\n)",
            Arr::print(self::LIST_ARRAY)
        );
        $this->assertEquals(
            "array (\n  'big_test' => 'ok',\n  'or' => 'Not',\n)",
            Arr::print(self::ASSOCIATIVE_ARRAY)
        );
    }

    /**
     * @return void
     * @throws ArrayKeyAlreadySet
     * @throws ExpectationFailedException
     */
    public function testPushValueIntoIndex(): void
    {
        $value = 'testing';
        $key = 'key';

        $this->assertEquals([$value], Arr::pushValueIntoIndex([], 0, $value));
        $this->assertEquals([$value], Arr::pushValueIntoIndex([], 7, $value));
        $this->assertEquals([$key => $value], Arr::pushValueIntoIndex([], 0, $value, $key));
        $this->assertEquals([$key => $value], Arr::pushValueIntoIndex([], 7, $value, $key));
        $this->assertEquals(['' => $value], Arr::pushValueIntoIndex([], 0, $value, ''));
        $this->assertEquals(['' => $value], Arr::pushValueIntoIndex([], 7, $value, ''));
        $this->assertEquals(
            Arr::append(self::LIST_ARRAY, $value),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, count(self::LIST_ARRAY), $value)
        );
        $this->assertEquals(
            Arr::append(self::LIST_ARRAY, $value),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, -count(self::LIST_ARRAY), $value)
        );
        $this->assertEquals(
            Arr::prepend(self::LIST_ARRAY, $value),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, 0, $value)
        );
        $this->assertEquals(
            [1, 2, $value, 3, 4],
            Arr::pushValueIntoIndex(self::LIST_ARRAY, 2, $value)
        );
        $this->assertEquals(
            [1, 2, $key => $value, 3, 4],
            Arr::pushValueIntoIndex(self::LIST_ARRAY, 2, $value, $key)
        );
        $this->assertEquals(
            [1, 2, '' => $value, 3, 4],
            Arr::pushValueIntoIndex(self::LIST_ARRAY, 2, $value, '')
        );
        $this->assertEquals(
            Arr::append(self::LIST_ARRAY, $value, $key),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, count(self::LIST_ARRAY), $value, $key)
        );
        $this->assertEquals(
            Arr::append(self::LIST_ARRAY, $value, ''),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, count(self::LIST_ARRAY), $value, '')
        );
        $this->assertEquals(
            Arr::append(self::LIST_ARRAY, $value, $key),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, -count(self::LIST_ARRAY), $value, $key)
        );
        $this->assertEquals(
            Arr::append(self::LIST_ARRAY, $value, ''),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, -count(self::LIST_ARRAY), $value, '')
        );
        $this->assertEquals(
            Arr::prepend(self::LIST_ARRAY, $value, $key),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, 0, $value, $key)
        );
        $this->assertEquals(
            Arr::prepend(self::LIST_ARRAY, $value, ''),
            Arr::pushValueIntoIndex(self::LIST_ARRAY, 0, $value, '')
        );
        $this->assertEquals(
            Arr::append(self::ASSOCIATIVE_ARRAY, $value),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, count(self::ASSOCIATIVE_ARRAY), $value)
        );
        $this->assertEquals(
            Arr::append(self::ASSOCIATIVE_ARRAY, $value),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, -count(self::ASSOCIATIVE_ARRAY), $value)
        );
        $this->assertEquals(
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, 0, $value)
        );
        $this->assertEquals(
            [
                'test' => 'yeah',
                'bool' => true,
                $value,
                'number' => 123,
                'maybe_null' => null,
                'list' => [
                    true,
                    false,
                    null,
                    45,
                    'told_ya',
                    self::ASSOCIATIVE_ARRAY,
                    self::LIST_ARRAY,
                ],
                'sub_object' => ['what' => 'is', 'done' => 80],
            ],
            Arr::pushValueIntoIndex(self::JSON_ARRAY, 2, $value)
        );
        $this->assertEquals(
            [
                'test' => 'yeah',
                'bool' => true,
                $key => $value,
                'number' => 123,
                'maybe_null' => null,
                'list' => [
                    true,
                    false,
                    null,
                    45,
                    'told_ya',
                    self::ASSOCIATIVE_ARRAY,
                    self::LIST_ARRAY,
                ],
                'sub_object' => ['what' => 'is', 'done' => 80],
            ],
            Arr::pushValueIntoIndex(self::JSON_ARRAY, 2, $value, $key)
        );
        $this->assertEquals(
            [
                'test' => 'yeah',
                'bool' => true,
                '' => $value,
                'number' => 123,
                'maybe_null' => null,
                'list' => [
                    true,
                    false,
                    null,
                    45,
                    'told_ya',
                    self::ASSOCIATIVE_ARRAY,
                    self::LIST_ARRAY,
                ],
                'sub_object' => ['what' => 'is', 'done' => 80],
            ],
            Arr::pushValueIntoIndex(self::JSON_ARRAY, 2, $value, '')
        );
        $this->assertEquals(
            ['a' => 'b', 2 => $value, 0 => 3, 1 => 4, 'c' => 'd'],
            Arr::pushValueIntoIndex(['a' => 'b', 3, 4, 'c' => 'd'], 1, $value)
        );
        $this->assertEquals(
            ['a' => 'b', $key => $value, 3, 4, 'c' => 'd'],
            Arr::pushValueIntoIndex(['a' => 'b', 3, 4, 'c' => 'd'], 1, $value, $key)
        );
        $this->assertEquals(
            ['a' => 'b', '' => $value, 3, 4, 'c' => 'd'],
            Arr::pushValueIntoIndex(['a' => 'b', 3, 4, 'c' => 'd'], 1, $value, '')
        );
        $this->assertEquals(
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, $key),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, count(self::ASSOCIATIVE_ARRAY), $value, $key)
        );
        $this->assertEquals(
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, ''),
            Arr::pushValueIntoIndex(
                self::ASSOCIATIVE_ARRAY,
                count(self::ASSOCIATIVE_ARRAY),
                $value,
                ''
            )
        );
        $this->assertEquals(
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, $key),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, -count(self::ASSOCIATIVE_ARRAY), $value, $key)
        );
        $this->assertEquals(
            Arr::append(self::ASSOCIATIVE_ARRAY, $value, ''),
            Arr::pushValueIntoIndex(
                self::ASSOCIATIVE_ARRAY,
                -count(self::ASSOCIATIVE_ARRAY),
                $value,
                ''
            )
        );
        $this->assertEquals(
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value, $key),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, 0, $value, $key)
        );
        $this->assertEquals(
            Arr::prepend(self::ASSOCIATIVE_ARRAY, $value, ''),
            Arr::pushValueIntoIndex(self::ASSOCIATIVE_ARRAY, 0, $value, '')
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRecursiveJoin(): void
    {
        $this->assertEquals([], Arr::recursiveJoin([], []));
        $this->assertEquals(self::LIST_ARRAY, Arr::recursiveJoin(self::LIST_ARRAY, []));
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::recursiveJoin(self::ASSOCIATIVE_ARRAY, [])
        );
        $this->assertEquals(self::LIST_ARRAY, Arr::recursiveJoin([], self::LIST_ARRAY));
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::recursiveJoin([], self::ASSOCIATIVE_ARRAY)
        );
        $this->assertEquals(
            self::LIST_ARRAY,
            Arr::recursiveJoin(self::LIST_ARRAY, self::LIST_ARRAY)
        );
        $this->assertEquals(
            self::ASSOCIATIVE_ARRAY,
            Arr::recursiveJoin(self::ASSOCIATIVE_ARRAY, self::ASSOCIATIVE_ARRAY)
        );
        $this->assertEquals(
            [1, 2, 3, 4, 'big_test' => 'ok', 'or' => 'Not'],
            Arr::recursiveJoin(self::LIST_ARRAY, self::ASSOCIATIVE_ARRAY)
        );
        $this->assertEquals(
            ['big_test' => 'ok', 'or' => 'Not', 1, 2, 3, 4],
            Arr::recursiveJoin(self::ASSOCIATIVE_ARRAY, self::LIST_ARRAY)
        );
        $this->assertEquals(
            [0, 1, 2, 4],
            Arr::recursiveJoin(self::LIST_ARRAY, [0, 1, 2])
        );
        $this->assertEquals(
            [0, 1, 2, 4, 87 => 94],
            Arr::recursiveJoin(self::LIST_ARRAY, [0, 1, 2, 87 => 94])
        );
        $this->assertEquals(
            ['big_test' => 'ok', 'or' => 'Yep'],
            Arr::recursiveJoin(self::ASSOCIATIVE_ARRAY, ['or' => 'Yep'])
        );
        $this->assertEquals(
            ['big_test' => 'failure', 'or' => 'Not', 'h' => 'o'],
            Arr::recursiveJoin(self::ASSOCIATIVE_ARRAY, ['big_test' => 'failure', 'h' => 'o'])
        );
        $this->assertEquals(
            [
                'test' => 'yeah',
                'bool' => true,
                'number' => 100,
                'maybe_null' => null,
                'list' => [
                    true,
                    false,
                    null,
                    45,
                    'told_ya',
                    ['big_test' => 77, 'or' => 'Not', 'hi'],
                    'poof!',
                    'test',
                ],
                'sub_object' => ['what' => 'is', 'done' => 80],
                1, 2, 3, 4,
                'big_test' => 'ok', 'or' => 'Yep',
                7,
                '' => 'final',
            ],
            Arr::recursiveJoin(
                self::JSON_ARRAY,
                self::LIST_ARRAY,
                self::ASSOCIATIVE_ARRAY,
                [
                    'number' => 100,
                    'list' => [7 => 'test', 5 => ['big_test' => 77, 'hi'], 6 => 'poof!'],
                    4 => 7,
                    'or' => 'Yep',
                ],
                ['' => 'final']
            )
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testSearchValueKey()
    {
        $this->assertNull(Arr::searchValueKey([], 'i'));
        $this->assertNull(Arr::searchValueKey(self::LIST_ARRAY, 'i'));
        $this->assertNull(Arr::searchValueKey(self::ASSOCIATIVE_ARRAY, 'i'));
        $this->assertEquals(1, Arr::searchValueKey(self::LIST_ARRAY, 2));
        $this->assertEquals('big_test', Arr::searchValueKey(self::ASSOCIATIVE_ARRAY, 'ok'));
        $this->assertEquals('a', Arr::searchValueKey(['a' => 'b', 'c' => 'b'], 'b'));
        $this->assertEquals('', Arr::searchValueKey(['d' => '', '' => 'b', 'c' => 'b'], 'b'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testSize(): void
    {
        $this->assertEquals(count([]), Arr::size([]));
        $this->assertEquals(count(self::LIST_ARRAY), Arr::size(self::LIST_ARRAY));
        $this->assertEquals(count(self::ASSOCIATIVE_ARRAY), Arr::size(self::ASSOCIATIVE_ARRAY));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    public function testSubjectDto(): void
    {
        $this->assertSubjectDtoMethods(['wrap', 'of']);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $this->assertEquals('[]', Arr::toJson([]));
        $this->assertEquals(StrHelperTest::JSON_STRING, Arr::toJson(self::JSON_ARRAY));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToParseArrayKey
     */
    public function testUnwrap(): void
    {
        $list_specific_key = 2;

        $this->assertNull(Arr::unwrap([]));
        $this->assertNull(Arr::unwrap([], 'i'));
        $this->assertNull(Arr::unwrap(self::LIST_ARRAY, 'i'));
        $this->assertNull(Arr::unwrap(self::ASSOCIATIVE_ARRAY, 'i'));
        $this->assertNull(Arr::unwrap([], ''));
        $this->assertNull(Arr::unwrap(self::LIST_ARRAY, ''));
        $this->assertNull(Arr::unwrap(self::ASSOCIATIVE_ARRAY, ''));
        $this->assertEquals(Arr::first(self::LIST_ARRAY), Arr::unwrap(self::LIST_ARRAY));
        $this->assertEquals(
            Arr::get(self::LIST_ARRAY, $list_specific_key),
            Arr::unwrap(self::LIST_ARRAY, $list_specific_key)
        );
        $this->assertEquals(Arr::first(['' => 8]), Arr::unwrap(['' => 8]));
        $this->assertEquals(Arr::get(['' => 8], ''), Arr::unwrap(['' => 8], ''));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testWrap(): void
    {
        $this->assertEquals([], Arr::wrap([]));
        $this->assertEquals(self::LIST_ARRAY, Arr::wrap(self::LIST_ARRAY));
        $this->assertEquals(self::ASSOCIATIVE_ARRAY, Arr::wrap(self::ASSOCIATIVE_ARRAY));
        $this->assertEquals(self::JSON_ARRAY, Arr::wrap(self::JSON_ARRAY));
        $this->assertEquals(['i'], Arr::wrap('i'));
    }

    /**
     * @param ReflectionMethod $subjectMethod
     * @param ReflectionMethod $helperMethod
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertSimilarMethodsReturnTypes(
        ReflectionMethod $subjectMethod,
        ReflectionMethod $helperMethod
    ): void
    {
        $subject_method_return_type = Str::replace(
            (string)$subjectMethod->getReturnType(),
            ['static', 'self'],
            ArraySubjectDTO::class
        );

        match ($subjectMethod->name) {
            default => $this->assertEquals(
                Str::replace((string)$helperMethod->getReturnType(), 'array', ArraySubjectDTO::class),
                $subject_method_return_type,
                "Failed asserting that helper and subject method $helperMethod->name return types are equal."
            )
        };
    }

    private function subject(): array
    {
        return [];
    }
}

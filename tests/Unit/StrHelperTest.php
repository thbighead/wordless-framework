<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use Random\RandomException;
use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO;
use Wordless\Application\Helpers\Str\Enums\Encoding;
use Wordless\Application\Helpers\Str\Exceptions\JsonDecodeError;
use Wordless\Tests\Unit\StrHelperTest\Traits\BooleanTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\SubstringTests;
use Wordless\Tests\Unit\StrHelperTest\Traits\UuidTests;
use Wordless\Tests\WordlessTestCase;
use Wordless\Tests\WordlessTestCase\Traits\SubjectDtoHelperTests;

class StrHelperTest extends WordlessTestCase
{
    use BooleanTests;
    use MutatorsTests;
    use UuidTests;
    use SubjectDtoHelperTests;
    use SubstringTests;

    public const JSON_STRING =
        '{"test":"yeah","bool":true,"number":123,"maybe_null":null,"list":[true,false,null,45,"told_ya",{"big_test":"ok","or":"Not"},[1,2,3,4]],"sub_object":{"what":"is","done":80}}';
    private const BASE_STRING = 'TestStringSubstrings';
    private const COUNT_STRING = 'Test Test Test Test';

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws JsonDecodeError
     */
    public function testJsonDecode(): void
    {
        $this->assertEquals([], Str::jsonDecode('[]'));
        $this->assertEquals([], Str::jsonDecode('{}'));
        $this->assertEquals(
            ArrHelperTest::JSON_ARRAY,
            Str::jsonDecode(self::JSON_STRING)
        );

        $this->expectException(JsonDecodeError::class);
        Str::jsonDecode('');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws JsonDecodeError
     * @throws PathNotFoundException
     */
    public function testJsonDecodeWithFiles(): void
    {
        $this->assertTrue(
            is_array($json = Str::jsonDecode(ProjectPath::root('composer.json')))
            && !empty($json)
        );

        $this->expectException(JsonDecodeError::class);
        Str::jsonDecode(ProjectPath::root('.gitignore'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testLength(): void
    {
        $this->assertEquals(4, Str::length('àäáã'));
        $this->assertEquals(19, Str::length(self::COUNT_STRING));
        $this->assertEquals(8, Str::length('àäáã', Encoding::ASCII));
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

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    public function testSubjectDto(): void
    {
        $this->assertSubjectDtoMethods(['random', 'uuid', 'of']);
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
            StringSubjectDTO::class
        );

        match ($subjectMethod->name) {
            'jsonDecode' => $this->assertEquals(
                Str::replace((string)$helperMethod->getReturnType(), 'array', ArraySubjectDTO::class),
                $subject_method_return_type,
                "Failed asserting subject method $subjectMethod->name return type."
            ),
            default => $this->assertEquals(
                Str::replace((string)$helperMethod->getReturnType(), 'string', StringSubjectDTO::class),
                $subject_method_return_type,
                "Failed asserting that helper and subject method $helperMethod->name return types are equal."
            )
        };
    }

    private function subject(): string
    {
        return self::BASE_STRING;
    }
}

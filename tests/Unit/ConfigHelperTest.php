<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Application\Helpers\Arr\Exceptions\ArrayKeyAlreadySet;
use Wordless\Application\Helpers\Arr\Exceptions\EmptyArrayHasNoIndex;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\WordlessTestCase;
use Wordless\Tests\WordlessTestCase\Traits\SubjectDtoHelperTests;

class ConfigHelperTest extends WordlessTestCase
{
    use SubjectDtoHelperTests;

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    public function testSubjectDto(): void
    {
        $this->assertSubjectDtoMethods([
            'get',
            'getOrFail',
            'of',
            'wordless',
            'wordlessCors',
            'wordlessCsp',
            'wordlessDatabase',
            'wordlessPluginsOrder',
            'wordpress',
            'wordpressAdmin',
            'wordpressLanguages',
            'wordpressTheme',
        ]);
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
            ConfigSubjectDTO::class
        );

        match ($subjectMethod->name) {
            default => $this->assertEquals(
                (string)$helperMethod->getReturnType(),
                $subject_method_return_type,
                "Failed asserting that helper and subject method $helperMethod->name return types are equal."
            )
        };
    }

    /**
     * @param ReflectionMethod $helperMethod
     * @return ReflectionParameter[]
     */
    private function getHelperMethodParameters(ReflectionMethod $helperMethod): array
    {
        return $helperMethod->getParameters();
    }

    private function subject(): string
    {
        return Config::FILE_WORDLESS;
    }
}

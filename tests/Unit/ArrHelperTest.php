<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\WordlessTestCase;
use Wordless\Tests\WordlessTestCase\Traits\SubjectDtoHelperTests;

class ArrHelperTest extends WordlessTestCase
{
    use SubjectDtoHelperTests;

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

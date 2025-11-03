<?php declare(strict_types=1);

namespace Wordless\Tests\WordlessTestCase\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Reflection;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Helper\Contracts\Subjectable;
use Wordless\Tests\WordlessTestCase;

/**
 * @mixin WordlessTestCase
 */
trait SubjectDtoHelperTests
{
    abstract public function testSubjectDto(): void;

    abstract private function assertSimilarMethodsReturnTypes(
        ReflectionMethod $subjectMethod,
        ReflectionMethod $helperMethod
    ): void;

    abstract private function subject(): mixed;

    /**
     * @param ReflectionMethod $subjectMethod
     * @param ReflectionMethod $helperMethod
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertSimilarMethods(ReflectionMethod $subjectMethod, ReflectionMethod $helperMethod): void
    {
        $this->assertTrue(
            $subjectMethod->isPublic(),
            "Failed asserting subject method $subjectMethod->name is public."
        );
        $this->assertFalse(
            $subjectMethod->isStatic(),
            "Failed asserting subject method $subjectMethod->name is not static."
        );

        $this->assertSimilarMethodsParameters($subjectMethod, $helperMethod);
        $this->assertSimilarMethodsReturnTypes($subjectMethod, $helperMethod);
    }

    /**
     * @param ReflectionMethod $subjectMethod
     * @param ReflectionMethod $helperMethod
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertSimilarMethodsParameters(ReflectionMethod $subjectMethod, ReflectionMethod $helperMethod): void
    {
        $helperMethodParameters = $helperMethod->getParameters();
        $subjectMethodParameters = $subjectMethod->getParameters();

        for ($i = 1; $i < count($helperMethodParameters); $i++) {
            $helperMethodParameter = $helperMethodParameters[$i];
            $subjectMethodParameter = $subjectMethodParameters[$i - 1];

            $this->assertEquals(
                $helperMethodParameter->name,
                $subjectMethodParameter->name,
                "Failed asserting helper and subject methods' $helperMethod->name parameter name are equals."
            );

            try {
                $helper_method_parameter_default_value = $helperMethodParameter->getDefaultValue();
            } catch (ReflectionException $exception) {
                $helper_method_parameter_default_value = $exception->getMessage();
            }

            try {
                $subject_method_parameter_default_value = $subjectMethodParameter->getDefaultValue();
            } catch (ReflectionException $exception) {
                $subject_method_parameter_default_value = $exception->getMessage();
            }

            $this->assertEquals(
                $helper_method_parameter_default_value,
                $subject_method_parameter_default_value,
                "Failed asserting that helper and subject $helperMethod->name method parameter $helperMethodParameter->name default value are equals."
            );
            $this->assertEquals(
                $helperMethodParameter->isOptional(),
                $subjectMethodParameter->isOptional(),
                "Failed asserting that helper and subject $helperMethod->name method parameter $helperMethodParameter->name are optional."
            );
            $this->assertEquals(
                $helperMethodParameter->isPassedByReference(),
                $subjectMethodParameter->isPassedByReference(),
                "Failed asserting that helper and subject $helperMethod->name method parameter $helperMethodParameter->name are passed by reference."
            );
            $this->assertEquals(
                $helperMethodParameter->isPromoted(),
                $subjectMethodParameter->isPromoted(),
                "Failed asserting that helper and subject $helperMethod->name method parameter $helperMethodParameter->name are promoted."
            );
            $this->assertEquals(
                $helperMethodParameter->isVariadic(),
                $subjectMethodParameter->isVariadic(),
                "Failed asserting that helper and subject $helperMethod->name method parameter $helperMethodParameter->name are variadic."
            );
            $this->assertEquals(
                (string)$helperMethodParameter->getType(),
                (string)$subjectMethodParameter->getType(),
                "Failed asserting that helper and subject $helperMethod->name method parameter $helperMethodParameter->name types are equal."
            );
        }
    }

    /**
     * @param string[] $exceptions
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    private function assertSubjectDtoMethods(array $exceptions = []): void
    {
        $subject = $this->helperClassNamespace()::of($this->subject());
        $exceptions = Arr::of($exceptions);
        $reflectedHelper = Reflection::getReflectionClass($this->helperClassNamespace());
        $reflectedSubject = Reflection::getReflectionObject($subject);

        foreach ($reflectedHelper->getMethods(ReflectionMethod::IS_PUBLIC) as $helperPublicStaticMethod) {
            if (!$helperPublicStaticMethod->isStatic()
                || Str::beginsWith($helperPublicStaticMethod->name, '_')
                || $exceptions->hasKey($helperPublicStaticMethod->name)
                || $exceptions->hasValue($helperPublicStaticMethod->name)) {
                continue;
            }

            $this->assertTrue(
                $reflectedSubject->hasMethod($helperPublicStaticMethod->name),
                'Failed asserting ' . $subject::class . " object has method $helperPublicStaticMethod->name"
            );

            $this->assertSimilarMethods(
                $reflectedSubject->getMethod($helperPublicStaticMethod->name),
                $helperPublicStaticMethod
            );
        }
    }

    /**
     * @return class-string|Subjectable
     */
    private function helperClassNamespace(): string|Subjectable
    {
        return '\\Wordless\\Application\\Helpers\\' . Str::of(static::class)->afterLast('\\')
                ->beforeLast('HelperTest');
    }
}

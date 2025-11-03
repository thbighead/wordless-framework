<?php declare(strict_types=1);

namespace Wordless\Tests\WordlessTestCase\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
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
    abstract private function assertSimilarReturnType(
        ReflectionType $subjectReturnType,
        ReflectionType $helperReturnType
    ): void;

    abstract private function subject(): mixed;

    abstract public function testSubjectDto(): void;

    /**
     * @param ReflectionIntersectionType $subjectType
     * @param ReflectionIntersectionType $helperType
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertReflectedParameterIntersectionType(
        ReflectionIntersectionType $subjectType,
        ReflectionIntersectionType $helperType
    ): void
    {
        $this->assertParametersReflectedTypes($subjectType->getTypes(), $helperType->getTypes());
    }

    /**
     * @param ReflectionNamedType $subjectType
     * @param ReflectionNamedType $helperType
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertReflectedParameterNamedType(ReflectionNamedType $subjectType, ReflectionNamedType $helperType): void
    {
        $this->assertEquals($helperType->getName(), $subjectType->getName());
        $this->assertEquals(
            $helperType->allowsNull(),
            $subjectType->allowsNull(),
            "Failed asserting helper parameter type {$helperType->getName()} and subject parameter type {$subjectType->getName()} both allows null."
        );
        $this->assertEquals(
            $helperType->isBuiltin(),
            $subjectType->isBuiltin(),
            "Failed asserting helper parameter type {$helperType->getName()} and subject parameter type {$subjectType->getName()} both are builtin."
        );
    }

    /**
     * @param ReflectionType $subjectType
     * @param ReflectionType $helperType
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertReflectedParameterType(ReflectionType $subjectType, ReflectionType $helperType): void
    {
        $this->assertEquals(
            $helperType::class,
            $subjectType::class,
            'Failed asserting parameters reflected classes.'
        );

        match (true) {
            $helperType instanceof ReflectionIntersectionType
            && $subjectType instanceof ReflectionIntersectionType => $this->assertReflectedParameterIntersectionType(
                $subjectType,
                $helperType
            ),
            $helperType instanceof ReflectionNamedType
            && $subjectType instanceof ReflectionNamedType => $this->assertReflectedParameterNamedType(
                $subjectType,
                $helperType
            ),
            $helperType instanceof ReflectionUnionType
            && $subjectType instanceof ReflectionUnionType => $this->assertReflectedParameterUnionType(
                $subjectType,
                $helperType
            ),
            default => throw new ExpectationFailedException('Types are not similar')
        };
    }

    /**
     * @param ReflectionUnionType $subjectType
     * @param ReflectionUnionType $helperType
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertReflectedParameterUnionType(ReflectionUnionType $subjectType, ReflectionUnionType $helperType): void
    {
        $this->assertParametersReflectedTypes($subjectType->getTypes(), $helperType->getTypes());
    }

    /**
     * @param ReflectionType[] $subject_types
     * @param ReflectionType[] $helper_types
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertParametersReflectedTypes(array $subject_types, array $helper_types): void
    {
        $this->assertEquals(
            $types_count = count($helper_types),
            count($subject_types),
            'Failed asserting subject and helper parameters types count.'
        );

        for ($i = 0; $i < $types_count; $i++) {
            $this->assertReflectedParameterType($subject_types[$i], $helper_types[$i]);
        }
    }

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
                "Failed asserting that helper and subject methods' parameter $helperMethodParameter->name default value are equals."
            );
            $this->assertEquals(
                $helperMethodParameter->isOptional(),
                $subjectMethodParameter->isOptional(),
                "Failed asserting that helper and subject methods' parameter $helperMethodParameter->name are optional."
            );
            $this->assertEquals(
                $helperMethodParameter->isPassedByReference(),
                $subjectMethodParameter->isPassedByReference(),
                "Failed asserting that helper and subject methods' parameter $helperMethodParameter->name are passed by reference."
            );
            $this->assertEquals(
                $helperMethodParameter->isPromoted(),
                $subjectMethodParameter->isPromoted(),
                "Failed asserting that helper and subject methods' parameter $helperMethodParameter->name are promoted."
            );
            $this->assertEquals(
                $helperMethodParameter->isVariadic(),
                $subjectMethodParameter->isVariadic(),
                "Failed asserting that helper and subject methods' parameter $helperMethodParameter->name are variadic."
            );

            $this->assertParametersReflectedTypes(
                $this->getParameterTypes($subjectMethodParameter),
                $this->getParameterTypes($helperMethodParameter)
            );
        }
    }

    /**
     * @param ReflectionMethod $subjectMethod
     * @param ReflectionMethod $helperMethod
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertSimilarMethodsReturnTypes(ReflectionMethod $subjectMethod, ReflectionMethod $helperMethod): void
    {
        $this->assertSimilarReturnType(
            $subjectMethod->getReturnType(),
            $helperMethod->getReturnType()
        );
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
     * @param ReflectionParameter|null $reflectedParameter
     * @return ReflectionType[]
     */
    private function getParameterTypes(?ReflectionParameter $reflectedParameter): array
    {
        if (is_null($type = $reflectedParameter->getType())) {
            return [];
        }

        if ($type instanceof ReflectionNamedType) {
            return [$type];
        }

        return $type->getTypes();
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

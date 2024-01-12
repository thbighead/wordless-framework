<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Wordless\Application\Helpers\Reflection\Contracts\Subjectable;

class Reflection extends Subjectable
{
    /**
     * @param object $object
     * @param string $method
     * @param string ...$arguments
     * @return mixed
     * @throws ReflectionException
     */
    public static function callNonPublicMethod(object $object, string $method, string ...$arguments): mixed
    {
        return (new ReflectionMethod($object, $method))->invoke($object, ...$arguments);
    }

    /**
     * @param object $object
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    public static function getNonPublicPropertyValue(object $object, string $property): mixed
    {
        return (new ReflectionProperty($object, $property))->getValue($object);
    }

    public static function getNonPublicConstValue(object $object, string $constant): mixed
    {
        return (new ReflectionClassConstant($object, $constant))->getValue();
    }

    public static function getReflectionClass(object $object): ReflectionClass
    {
        return new ReflectionClass($object);
    }
}

<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use Wordless\Application\Helpers\Reflection\Contracts\Subjectable;

class Reflection extends Subjectable
{
    /**
     * @param object|string $object
     * @param string $method
     * @param string ...$arguments
     * @return mixed
     * @throws ReflectionException
     */
    public static function callNonPublicMethod(object|string $object, string $method, string ...$arguments): mixed
    {
        return (new ReflectionMethod($object, $method))->invoke($object, ...$arguments);
    }

    /**
     * @param object|string $object
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    public static function getNonPublicPropertyValue(object|string $object, string $property): mixed
    {
        return (new ReflectionProperty($object, $property))->getValue($object);
    }

    public static function getNonPublicConstantValue(object|string $object, string $constant): mixed
    {
        return (new ReflectionClassConstant($object, $constant))->getValue();
    }

    /**
     * @param object|string $object
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public static function getReflectionClass(object|string $object): ReflectionClass
    {
        return new ReflectionClass($object);
    }

    /**
     * @param object $object
     * @return ReflectionObject
     */
    public static function getReflectionObject(object $object): ReflectionObject
    {
        return new ReflectionObject($object);
    }
}

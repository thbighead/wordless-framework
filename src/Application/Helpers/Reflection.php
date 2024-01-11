<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use ReflectionClass;
use ReflectionException;

class Reflection
{
    /**
     * @param object|string $objectOrClass
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    public static function getClassPropertyValue(object|string $objectOrClass, string $property): mixed
    {
        return (new ReflectionClass($objectOrClass))->getProperty($property)->getValue($objectOrClass);
    }
}

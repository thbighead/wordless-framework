<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use BadMethodCallException;
use ReflectionClass;
use ReflectionException;

/**
 * @mixin self
 */
class Reflection
{
    private readonly object $classInstance;
    private readonly ReflectionClass $reflectionClass;

    /**
     * @param object|string $objectOrClass
     * @throws ReflectionException
     */
    public function __construct(object|string $objectOrClass)
    {
        $this->classInstance = is_string($objectOrClass) ? new $objectOrClass : $objectOrClass;
        $this->reflectionClass = new ReflectionClass($this->classInstance);
    }

    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    public function callPrivateMethod(string $method, array $args = [])
    {
        $method = $this->getReflectionClass()->getMethod($method);
        /** @noinspection PhpExpressionResultUnusedInspection */
        $method->setAccessible(true);

        return $method->invoke($this->classInstance, ...$args);

    }

    /**
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function getPropertyValue(string $property): mixed
    {
        return $this->getReflectionClass()->getProperty($property)->getValue($this->classInstance);
    }
}

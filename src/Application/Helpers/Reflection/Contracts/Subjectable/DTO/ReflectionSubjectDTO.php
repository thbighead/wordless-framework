<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Reflection\Contracts\Subjectable\DTO;

use ReflectionClass;
use ReflectionException;
use Wordless\Application\Helpers\Reflection;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class ReflectionSubjectDTO extends SubjectDTO
{
    public function __construct(object $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @return string
     */
    public function getOriginalSubject(): string
    {
        return parent::getOriginalSubject();
    }

    /**
     * @return object
     */
    public function getSubject(): object
    {
        return parent::getSubject();
    }

    public function getNonPublicConstValue(string $constant)
    {
        return Reflection::getNonPublicConstValue($this->getSubject(), $constant);
    }

    /**
     * @param string $method
     * @return mixed
     * @throws ReflectionException
     */
    public function callNonPublicMethod(string $method): mixed
    {
        return Reflection::callNonPublicMethod($this->getSubject(), $method);
    }

    /**
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function getNonPublicPropertyValue(string $property): mixed
    {
        return Reflection::getNonPublicPropertyValue($this->getSubject(), $property);
    }

    public function getReflectionClass(): ReflectionClass
    {
        return Reflection::getReflectionClass($this->getSubject());
    }
}

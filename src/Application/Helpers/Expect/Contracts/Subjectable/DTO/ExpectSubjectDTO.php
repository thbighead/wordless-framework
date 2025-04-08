<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Expect\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Expect;
use Wordless\Application\Helpers\Expect\Exceptions\ExpectedValueType;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class ExpectSubjectDTO extends SubjectDTO
{
    public function array(array $default = [], ?callable $criteria = null): self
    {
        try {
            return $this->arrayOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return self
     * @throws ExpectedValueType
     */
    public function arrayOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::arrayOrFail($this->subject, $criteria);

        return $this;
    }

    public function boolean(bool $default = false, ?callable $criteria = null): self
    {
        try {
            return $this->booleanOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function booleanOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::booleanOrFail($this->subject, $criteria);

        return $this;
    }

    public function classObject(
        string               $class_namespace,
        object|callable|null $default = null,
        ?callable            $criteria = null
    ): self
    {
        try {
            return $this->classObjectOrFail($class_namespace, $criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param string $class_namespace
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function classObjectOrFail(string $class_namespace, ?callable $criteria = null): self
    {
        $this->subject = Expect::classObjectOrFail($this->subject, $class_namespace, $criteria);

        return $this;
    }

    public function float(float $default = 0, ?callable $criteria = null): self
    {
        try {
            return $this->floatOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function floatOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::floatOrFail($this->subject, $criteria);

        return $this;
    }

    public function integer(int $default = 0, ?callable $criteria = null): self
    {
        try {
            return $this->integerOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function integerOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::integerOrFail($this->subject, $criteria);

        return $this;
    }

    public function list(array $default = [], ?callable $criteria = null): self
    {
        try {
            return $this->listOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function listOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::listOrFail($this->subject, $criteria);

        return $this;
    }

    public function object(object|callable|null $default = null, ?callable $criteria = null): self
    {
        try {
            return $this->objectOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function objectOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::objectOrFail($this->subject, $criteria);

        return $this;
    }

    public function string(string $default = '', ?callable $criteria = null): self
    {
        try {
            return $this->stringOrFail($criteria);
        } catch (ExpectedValueType) {
            $this->subject = $default;
        }

        return $this;
    }

    /**
     * @param callable|null $criteria
     * @return $this
     * @throws ExpectedValueType
     */
    public function stringOrFail(?callable $criteria = null): self
    {
        $this->subject = Expect::stringOrFail($this->subject, $criteria);

        return $this;
    }
}

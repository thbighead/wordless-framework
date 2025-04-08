<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Expect\Contracts\Subjectable;
use Wordless\Application\Helpers\Expect\Exceptions\ExpectedValueType;

class Expect extends Subjectable
{
    public static function array(mixed $subject, array $default = [], ?callable $criteria = null): array
    {
        try {
            return static::arrayOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return array
     * @throws ExpectedValueType
     */
    public static function arrayOrFail(mixed $subject, ?callable $criteria = null): array
    {
        if (!is_array($subject)) {
            throw new ExpectedValueType($subject, GetType::ARRAY);
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::arrayOrFail($criteria($subject));
    }

    public static function boolean(mixed $subject, bool $default = false, ?callable $criteria = null): bool
    {
        try {
            return static::booleanOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return bool
     * @throws ExpectedValueType
     */
    public static function booleanOrFail(mixed $subject, ?callable $criteria = null): bool
    {
        try {
            $subject = static::integerOrFail($subject);
        } catch (ExpectedValueType) {
        }

        $subject = match ($subject) {
            'true', 1 => true,
            'false', 0 => false,
            default => $subject,
        };

        if (!is_bool($subject)) {
            throw new ExpectedValueType($subject, GetType::BOOLEAN);
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::booleanOrFail($criteria($subject));
    }

    public static function classObject(
        mixed                $subject,
        string               $class_namespace,
        object|callable|null $default = null,
        ?callable            $criteria = null
    ): object
    {
        try {
            return static::classObjectOrFail($subject, $class_namespace, $criteria);
        } catch (ExpectedValueType) {
            if (is_callable($default)) {
                $default = $default();
            }

            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param string $class_namespace
     * @param callable|null $criteria
     * @return object
     * @throws ExpectedValueType
     */
    public static function classObjectOrFail(
        mixed     $subject,
        string    $class_namespace,
        ?callable $criteria = null
    ): object
    {
        $subject = static::objectOrFail($subject);

        if (GetType::of($subject) !== $class_namespace) {
            throw new ExpectedValueType($subject, $class_namespace);
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::classObjectOrFail($criteria($subject), $class_namespace);
    }

    public static function float(mixed $subject, float $default = 0, ?callable $criteria = null): float
    {
        try {
            return static::floatOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return float
     * @throws ExpectedValueType
     */
    public static function floatOrFail(mixed $subject, ?callable $criteria = null): float
    {
        if (is_numeric($subject)) {
            $subject = (float)$subject;
        }

        if (!is_float($subject)) {
            throw new ExpectedValueType($subject, GetType::DOUBLE . ' (float)');
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::floatOrFail($criteria($subject));
    }

    public static function integer(mixed $subject, int $default = 0, ?callable $criteria = null): int
    {
        try {
            return static::integerOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return int
     * @throws ExpectedValueType
     */
    public static function integerOrFail(mixed $subject, ?callable $criteria = null): int
    {
        if (is_numeric($subject)) {
            $subject = (int)$subject;
        }

        if (!is_int($subject)) {
            throw new ExpectedValueType($subject, GetType::INTEGER);
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::integerOrFail($criteria($subject));
    }

    public static function list(mixed $subject, array $default = [], ?callable $criteria = null): array
    {
        try {
            return static::listOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return array
     * @throws ExpectedValueType
     */
    public static function listOrFail(mixed $subject, ?callable $criteria = null): array
    {
        $subject = static::arrayOrFail($subject);

        if (Arr::isAssociative($subject)) {
            throw new ExpectedValueType($subject, 'list ' . GetType::ARRAY);
        }

        return static::listOrFail($criteria($subject));
    }

    public static function object(
        mixed                $subject,
        object|callable|null $default = null,
        ?callable            $criteria = null
    ): object
    {
        try {
            return static::objectOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            if (is_callable($default)) {
                $default = $default();
            }

            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return object
     * @throws ExpectedValueType
     */
    public static function objectOrFail(mixed $subject, ?callable $criteria = null): object
    {
        if (!is_object($subject)) {
            throw new ExpectedValueType($subject, GetType::OBJECT);
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::objectOrFail($criteria($subject));
    }

    public static function string(mixed $subject, string $default = '', ?callable $criteria = null): string
    {
        try {
            return static::stringOrFail($subject, $criteria);
        } catch (ExpectedValueType) {
            return $default;
        }
    }

    /**
     * @param mixed $subject
     * @param callable|null $criteria
     * @return string
     * @throws ExpectedValueType
     */
    public static function stringOrFail(mixed $subject, ?callable $criteria = null): string
    {
        if (GetType::isStringable($subject)) {
            $subject = (string)$subject;
        }

        if (!is_string($subject)) {
            throw new ExpectedValueType($subject, GetType::STRING);
        }

        if ($criteria === null) {
            return $subject;
        }

        return static::stringOrFail($criteria($subject));
    }
}

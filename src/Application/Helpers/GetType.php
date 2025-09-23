<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use DateTimeInterface;
use Wordless\Infrastructure\Helper;

class GetType extends Helper
{
    final public const ARRAY = 'array';
    final public const ASSOCIATIVE_ARRAY = 'associative ' . self::ARRAY;
    final public const BOOLEAN = 'boolean';
    final public const DOUBLE = 'double';
    final public const INTEGER = 'integer';
    final public const LIST_ARRAY = 'list ' . self::ARRAY;
    final public const NULL = 'NULL';
    final public const OBJECT = 'object';
    final public const STRING = 'string';

    public static function isDateable($value): bool
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        return strtotime($value) !== false;
    }

    public static function isStringable($value): bool
    {
        if (is_object($value) && method_exists($value, '__toString')) {
            return true;
        }

        if (is_null($value)) {
            return true;
        }

        return is_scalar($value);
    }

    public static function of($variable): string
    {
        return match ($type = gettype($variable)) {
            self::ARRAY => Arr::isAssociative($variable) ? self::ASSOCIATIVE_ARRAY : self::LIST_ARRAY,
            self::OBJECT => $variable::class,
            default => $type,
        };
    }
}

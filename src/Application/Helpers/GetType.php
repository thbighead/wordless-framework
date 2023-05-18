<?php

namespace Wordless\Application\Helpers;

use DateTimeInterface;

class GetType
{
    public const ARRAY = 'array';
    public const BOOLEAN = 'boolean';
    public const DOUBLE = 'double';
    public const INTEGER = 'integer';
    public const STRING = 'string';

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
        $type = gettype($variable);

        if ($type === 'object') {
            return $variable::class;
        }

        return $type;
    }
}

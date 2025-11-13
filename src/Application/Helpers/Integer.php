<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Random\RandomException;
use Wordless\Infrastructure\Helper;

class Integer extends Helper
{
    public static function random(int $min, int $max): int
    {
        if ($min === $max) {
            return $min;
        }

        if ($min > $max) {
            static::swap($min, $max);
        }

        try {
            return random_int($min, $max);
        } catch (RandomException) {
            return rand($min, $max);
        }
    }

    public static function swap(int &$value1, int &$value2): void
    {
        if ($value1 === $value2) {
            return;
        }

        $value1 = $value1 ^ $value2;
        $value2 = $value1 ^ $value2;
        $value1 = $value1 ^ $value2;
    }
}

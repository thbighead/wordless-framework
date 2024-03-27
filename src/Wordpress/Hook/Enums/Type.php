<?php declare(strict_types=1);

namespace Wordless\Wordpress\Hook\Enums;

enum Type
{
    case action;
    case filter;

    public static function casesListAsString(): string
    {
        return implode(', ', self::stringCasesList());
    }

    /**
     * @return string[]
     */
    public static function stringCasesList(): array
    {
        $string_cases = [];

        foreach (self::cases() as $case) {
            $string_cases[] = $case->name;
        }

        return $string_cases;
    }
}

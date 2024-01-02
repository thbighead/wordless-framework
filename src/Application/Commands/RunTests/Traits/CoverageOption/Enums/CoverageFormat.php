<?php

namespace Wordless\Application\Commands\RunTests\Traits\CoverageOption\Enums;

enum CoverageFormat: string
{
    case clover = 'clover';
    case cobertura = 'cobertura';
    case crap4j = 'crap4j';
    case html = 'html';
    case php = 'php';
    case text = 'text';

    public static function stringList(): string
    {
        $string_list = '';

        foreach (self::cases() as $case) {
            $string_list .= empty($string_list) ? $case->name : " $case->name";
        }

        return $string_list;
    }

    public function mountForCommand(): string
    {
        $base_command_part = "--coverage-$this->value";

        return match ($this) {
            self::text => $base_command_part,
            default => "$base_command_part tests/coverage/$this->value",
        };
    }
}

<?php

namespace StrHelperTest\Traits\CaseStyleTests\Traits;

use Wordless\Application\Helpers\Str;

trait FromRawToAnother
{
    public function testRawToTitleCase(): void
    {
        $this->assertEquals(
            Str::titleCase(self::CLEAN_RAW_CASE_EXAMPLE),
            self::CLEAN_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
            self::NUMERICAL_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(mb_strtoupper(self::CLEAN_RAW_CASE_EXAMPLE)),
            self::CLEAN_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(mb_strtoupper(self::NUMERICAL_RAW_CASE_EXAMPLE)),
            self::NUMERICAL_TITLE_CASE_EXAMPLE
        );
    }

    public function testRawToCamelCase(): void
    {
        $this->assertEquals(
            Str::camelCase(self::CLEAN_RAW_CASE_EXAMPLE),
            self::CLEAN_CAMEL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::camelCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
            self::NUMERICAL_CAMEL_CASE_EXAMPLE
        );
    }

    public function testRawToPascalCase(): void
    {
        $this->assertEquals(
            Str::pascalCase(self::CLEAN_RAW_CASE_EXAMPLE),
            self::CLEAN_PASCAL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::pascalCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
            self::NUMERICAL_PASCAL_CASE_EXAMPLE
        );
    }

    public function testRawToSnakeCase(): void
    {
        $this->assertEquals(
            Str::snakeCase(self::CLEAN_RAW_CASE_EXAMPLE),
            self::CLEAN_SNAKE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::snakeCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
            self::NUMERICAL_SNAKE_CASE_EXAMPLE
        );
    }

    public function testRawToKebabCase(): void
    {
        $this->assertEquals(
            Str::kebabCase(self::CLEAN_RAW_CASE_EXAMPLE),
            self::CLEAN_KEBAB_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::kebabCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
            self::NUMERICAL_KEBAB_CASE_EXAMPLE
        );
    }
}

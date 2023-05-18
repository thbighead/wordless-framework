<?php

namespace Wordless\Tests\Unit\StrHelperTest\CaseStyleTests;

use Wordless\Application\Helpers\Str;

trait FromPascalToAnother
{
    public function testPascalToTitleCase()
    {
        $this->assertEquals(
            Str::titleCase(self::CLEAN_PASCAL_CASE_EXAMPLE),
            self::CLEAN_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(self::NUMERICAL_PASCAL_CASE_EXAMPLE),
            self::NUMERICAL_TITLE_CASE_EXAMPLE
        );
    }

    public function testPascalToCamelCase()
    {
        $this->assertEquals(
            Str::camelCase(self::CLEAN_PASCAL_CASE_EXAMPLE),
            self::CLEAN_CAMEL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::camelCase(self::NUMERICAL_PASCAL_CASE_EXAMPLE),
            self::NUMERICAL_CAMEL_CASE_EXAMPLE
        );
    }

    public function testPascalToPascalCase()
    {
        $this->assertEquals(
            Str::pascalCase(self::CLEAN_PASCAL_CASE_EXAMPLE),
            self::CLEAN_PASCAL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::pascalCase(self::NUMERICAL_PASCAL_CASE_EXAMPLE),
            self::NUMERICAL_PASCAL_CASE_EXAMPLE
        );
    }

    public function testPascalToSnakeCase()
    {
        $this->assertEquals(
            Str::snakeCase(self::CLEAN_PASCAL_CASE_EXAMPLE),
            self::CLEAN_SNAKE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::snakeCase(self::NUMERICAL_PASCAL_CASE_EXAMPLE),
            self::NUMERICAL_SNAKE_CASE_EXAMPLE
        );
    }

    public function testPascalToKebabCase()
    {
        $this->assertEquals(
            Str::kebabCase(self::CLEAN_PASCAL_CASE_EXAMPLE),
            self::CLEAN_KEBAB_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::kebabCase(self::NUMERICAL_PASCAL_CASE_EXAMPLE),
            self::NUMERICAL_KEBAB_CASE_EXAMPLE
        );
    }
}

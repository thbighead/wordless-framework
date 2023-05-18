<?php

namespace Wordless\Tests\Unit\StrHelperTest\CaseStyleTests;

use Wordless\Application\Helpers\Str;

trait FromCamelToAnother
{
    public function testCamelToTitleCase()
    {
        $this->assertEquals(
            Str::titleCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
            self::CLEAN_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
            self::NUMERICAL_TITLE_CASE_EXAMPLE
        );
    }

    public function testCamelToCamelCase()
    {
        $this->assertEquals(
            Str::camelCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
            self::CLEAN_CAMEL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::camelCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
            self::NUMERICAL_CAMEL_CASE_EXAMPLE
        );
    }

    public function testCamelToPascalCase()
    {
        $this->assertEquals(
            Str::pascalCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
            self::CLEAN_PASCAL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::pascalCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
            self::NUMERICAL_PASCAL_CASE_EXAMPLE
        );
    }

    public function testCamelToSnakeCase()
    {
        $this->assertEquals(
            Str::snakeCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
            self::CLEAN_SNAKE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::snakeCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
            self::NUMERICAL_SNAKE_CASE_EXAMPLE
        );
    }

    public function testCamelToKebabCase()
    {
        $this->assertEquals(
            Str::kebabCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
            self::CLEAN_KEBAB_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::kebabCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
            self::NUMERICAL_KEBAB_CASE_EXAMPLE
        );
    }
}

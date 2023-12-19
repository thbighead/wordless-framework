<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits;

use Wordless\Application\Helpers\Str;

trait FromTitleToAnother
{
    public function testTitleToTitleCase(): void
    {
        $this->assertEquals(
            Str::titleCase(self::CLEAN_TITLE_CASE_EXAMPLE),
            self::CLEAN_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
            self::NUMERICAL_TITLE_CASE_EXAMPLE
        );
    }

    public function testTitleToCamelCase(): void
    {
        $this->assertEquals(
            Str::camelCase(self::CLEAN_TITLE_CASE_EXAMPLE),
            self::CLEAN_CAMEL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::camelCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
            self::NUMERICAL_CAMEL_CASE_EXAMPLE
        );
    }

    public function testTitleToPascalCase(): void
    {
        $this->assertEquals(
            Str::pascalCase(self::CLEAN_TITLE_CASE_EXAMPLE),
            self::CLEAN_PASCAL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::pascalCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
            self::NUMERICAL_PASCAL_CASE_EXAMPLE
        );
    }

    public function testTitleToSnakeCase(): void
    {
        $this->assertEquals(
            Str::snakeCase(self::CLEAN_TITLE_CASE_EXAMPLE),
            self::CLEAN_SNAKE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::snakeCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
            self::NUMERICAL_SNAKE_CASE_EXAMPLE
        );
    }

    public function testTitleToKebabCase(): void
    {
        $this->assertEquals(
            Str::kebabCase(self::CLEAN_TITLE_CASE_EXAMPLE),
            self::CLEAN_KEBAB_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::kebabCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
            self::NUMERICAL_KEBAB_CASE_EXAMPLE
        );
    }
}

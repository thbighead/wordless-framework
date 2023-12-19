<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits;

use Wordless\Application\Helpers\Str;

trait FromKebabToAnother
{
    public function testKebabToTitleCase(): void
    {
        $this->assertEquals(
            Str::titleCase(self::CLEAN_KEBAB_CASE_EXAMPLE),
            self::CLEAN_TITLE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::titleCase(self::NUMERICAL_KEBAB_CASE_EXAMPLE),
            self::NUMERICAL_TITLE_CASE_EXAMPLE
        );
    }

    public function testKebabToCamelCase(): void
    {
        $this->assertEquals(
            Str::camelCase(self::CLEAN_KEBAB_CASE_EXAMPLE),
            self::CLEAN_CAMEL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::camelCase(self::NUMERICAL_KEBAB_CASE_EXAMPLE),
            self::NUMERICAL_CAMEL_CASE_EXAMPLE
        );
    }

    public function testKebabToPascalCase(): void
    {
        $this->assertEquals(
            Str::pascalCase(self::CLEAN_KEBAB_CASE_EXAMPLE),
            self::CLEAN_PASCAL_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::pascalCase(self::NUMERICAL_KEBAB_CASE_EXAMPLE),
            self::NUMERICAL_PASCAL_CASE_EXAMPLE
        );
    }

    public function testKebabToSnakeCase(): void
    {
        $this->assertEquals(
            Str::snakeCase(self::CLEAN_KEBAB_CASE_EXAMPLE),
            self::CLEAN_SNAKE_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::snakeCase(self::NUMERICAL_KEBAB_CASE_EXAMPLE),
            self::NUMERICAL_SNAKE_CASE_EXAMPLE
        );
    }

    public function testKebabToKebabCase(): void
    {
        $this->assertEquals(
            Str::kebabCase(self::CLEAN_KEBAB_CASE_EXAMPLE),
            self::CLEAN_KEBAB_CASE_EXAMPLE
        );
        $this->assertEquals(
            Str::kebabCase(self::NUMERICAL_KEBAB_CASE_EXAMPLE),
            self::NUMERICAL_KEBAB_CASE_EXAMPLE
        );
    }
}

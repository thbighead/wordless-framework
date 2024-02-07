<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait FromRawToAnother
{
    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testRawToTitleCase(): void
    {
        $this->assertEquals(
            self::CLEAN_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::CLEAN_RAW_CASE_EXAMPLE)
        );
        $this->assertEquals(
            self::CLEAN_TITLE_CASE_EXAMPLE,
            Str::titleCase(mb_strtoupper(self::CLEAN_RAW_CASE_EXAMPLE)),
        );
        $this->assertEquals(
            self::NUMERICAL_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_TITLE_CASE_EXAMPLE,
            Str::titleCase(mb_strtoupper(self::NUMERICAL_RAW_CASE_EXAMPLE)),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testRawToCamelCase(): void
    {
        $this->assertEquals(
            self::CLEAN_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::CLEAN_CAMEL_CASE_EXAMPLE,
            Str::camelCase(mb_strtoupper(self::CLEAN_RAW_CASE_EXAMPLE)),
        );
        $this->assertEquals(
            self::NUMERICAL_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_CAMEL_CASE_EXAMPLE,
            Str::camelCase(mb_strtoupper(self::NUMERICAL_RAW_CASE_EXAMPLE)),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testRawToPascalCase(): void
    {
        $this->assertEquals(
            self::CLEAN_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::CLEAN_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(mb_strtoupper(self::CLEAN_RAW_CASE_EXAMPLE)),
        );
        $this->assertEquals(
            self::NUMERICAL_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(mb_strtoupper(self::NUMERICAL_RAW_CASE_EXAMPLE)),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testRawToSnakeCase(): void
    {
        $this->assertEquals(
            self::CLEAN_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::CLEAN_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(mb_strtoupper(self::CLEAN_RAW_CASE_EXAMPLE)),
        );
        $this->assertEquals(
            self::NUMERICAL_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(mb_strtoupper(self::NUMERICAL_RAW_CASE_EXAMPLE)),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testRawToKebabCase(): void
    {
        $this->assertEquals(
            self::CLEAN_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::CLEAN_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(mb_strtoupper(self::CLEAN_RAW_CASE_EXAMPLE)),
        );
        $this->assertEquals(
            self::NUMERICAL_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(mb_strtoupper(self::NUMERICAL_RAW_CASE_EXAMPLE)),
        );
    }
}

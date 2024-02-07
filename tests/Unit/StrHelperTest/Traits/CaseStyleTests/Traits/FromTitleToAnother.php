<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait FromTitleToAnother
{
    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testTitleToTitleCase(): void
    {
        $this->assertEquals(
            self::CLEAN_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::CLEAN_TITLE_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testTitleToCamelCase(): void
    {
        $this->assertEquals(
            self::CLEAN_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::CLEAN_TITLE_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testTitleToPascalCase(): void
    {
        $this->assertEquals(
            self::CLEAN_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::CLEAN_TITLE_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testTitleToSnakeCase(): void
    {
        $this->assertEquals(
            self::CLEAN_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::CLEAN_TITLE_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testTitleToKebabCase(): void
    {
        $this->assertEquals(
            self::CLEAN_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::CLEAN_TITLE_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::NUMERICAL_TITLE_CASE_EXAMPLE),
        );
    }
}

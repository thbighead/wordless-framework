<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait FromCamelToAnother
{
    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testCamelToTitleCase(): void
    {
        $this->assertEquals(
            self::CLEAN_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testCamelToCamelCase(): void
    {
        $this->assertEquals(
            self::CLEAN_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testCamelToPascalCase(): void
    {
        $this->assertEquals(
            self::CLEAN_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testCamelToSnakeCase(): void
    {
        $this->assertEquals(
            self::CLEAN_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testCamelToKebabCase(): void
    {
        $this->assertEquals(
            self::CLEAN_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::CLEAN_CAMEL_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::NUMERICAL_CAMEL_CASE_EXAMPLE),
        );
    }
}

<?php declare(strict_types=1);

namespace Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Tests\Unit\StrHelperTest;

/**
 * @mixin StrHelperTest
 */
trait FromRawToAnother
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function testRawToTitleCase(): void
    {
        $this->assertEquals(
            self::CLEAN_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::CLEAN_RAW_CASE_EXAMPLE)
        );
        $this->assertEquals(
            self::NUMERICAL_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::ACCENTED_TITLE_CASE_EXAMPLE,
            Str::titleCase(self::ACCENTED_RAW_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function testRawToCamelCase(): void
    {
        $this->assertEquals(
            self::CLEAN_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::ACCENTED_CAMEL_CASE_EXAMPLE,
            Str::camelCase(self::ACCENTED_RAW_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function testRawToPascalCase(): void
    {
        $this->assertEquals(
            self::CLEAN_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::ACCENTED_PASCAL_CASE_EXAMPLE,
            Str::pascalCase(self::ACCENTED_RAW_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function testRawToSnakeCase(): void
    {
        $this->assertEquals(
            self::CLEAN_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::ACCENTED_SNAKE_CASE_EXAMPLE,
            Str::snakeCase(self::ACCENTED_RAW_CASE_EXAMPLE),
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function testRawToKebabCase(): void
    {
        $this->assertEquals(
            self::CLEAN_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::CLEAN_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::NUMERICAL_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::NUMERICAL_RAW_CASE_EXAMPLE),
        );
        $this->assertEquals(
            self::ACCENTED_KEBAB_CASE_EXAMPLE,
            Str::kebabCase(self::ACCENTED_RAW_CASE_EXAMPLE),
        );
    }
}

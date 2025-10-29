<?php declare(strict_types=1);

namespace Wordless\Tests\Unit\StrHelperTest\Traits\MutatorsTests\Traits\CaseStyleTests\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Tests\Unit\StrHelperTest;

/**
 * @mixin StrHelperTest
 */
trait FromTitleToAnother
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
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
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
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
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
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
     * @throws ExpectationFailedException
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
     * @throws ExpectationFailedException
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

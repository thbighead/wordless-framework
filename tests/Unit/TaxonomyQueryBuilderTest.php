<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use Generator;
use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use TaxonomyQueryBuilderTest\DTO\QueryBuildersDTO;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Tests\WordlessTestCase\QueryBuilderTestCase;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

class TaxonomyQueryBuilderTest extends QueryBuilderTestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws ReflectionException
     */
    public function testOnlyAvailableInAdminMenu(): void
    {
        $this->testAllQueryBuilders('show_ui', true);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws ReflectionException
     */
    public function testOnlyAvailableInRestApi(): void
    {
        $this->testAllQueryBuilders('show_in_rest', true);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws ReflectionException
     */
    public function testOnlyCustom(): void
    {
        $this->testAllQueryBuilders('_builtin', false);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws ReflectionException
     */
    public function testWhereAdminMenuSingularLabel(): void
    {
        $this->testAllQueryBuilders('singular_label', $value = 'test', [$value]);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws ReflectionException
     */
    public function testWhereCanOnlyBeUsedBy(): void
    {
        $expected_argument_key = 'object_type';
        $value1 = ObjectType::post;
        $value2 = ObjectType::user;

        $this->testAllQueryBuilders($expected_argument_key, [$value1->name], [$value1]);
        $this->testAllQueryBuilders($expected_argument_key, [$value1->name, $value2->name], [$value1, $value2]);
    }

    /**
     * @return Generator<QueryBuildersDTO>
     */
    private function queryBuilders(): Generator
    {
        foreach (ResultFormat::cases() as $format) {
            foreach (Operator::cases() as $operator) {
                yield new QueryBuildersDTO($format, $operator);
            }
        }
    }

    /**
     * @param string $expected_argument_key
     * @param string|bool|array $expected_argument_value
     * @param array $method_parameters
     * @param string $test_method
     * @return void
     * @throws ExpectationFailedException
     * @throws FailedToCreateInflector
     * @throws ReflectionException
     */
    private function testAllQueryBuilders(
        string $expected_argument_key,
        string|bool|array $expected_argument_value,
        array $method_parameters = [],
        string $test_method = __METHOD__
    ): void
    {
        $method = Str::of($test_method)->after('test')->camelCase();

        foreach ($this->queryBuilders() as $taxonomyQueryBuilder) {
            $this->assertBuiltArguments([
                $expected_argument_key => $expected_argument_value
            ], $taxonomyQueryBuilder->queryBuilder->$method(...$method_parameters));
        }
    }
}

<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder;

use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\TermInstantiationError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Delete\Exceptions\DeleteTermError;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update\UpdateBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\Exceptions\FailedToResolveCallResult;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\Exceptions\FailedToUpdateTerms;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\Exceptions\UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
use WP_Term;

/**
 * @mixin TermQueryBuilder
 */
class TermModelQueryBuilder
{
    private TermQueryBuilder $queryBuilder;

    /**
     * @param string $model_class_namespace
     * @return static
     * @throws InvalidModelClass
     */
    public static function make(string $model_class_namespace): static
    {
        return new static($model_class_namespace);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return $this|array|bool|int|string|Taxonomy|null
     * @throws FailedToResolveCallResult
     * @throws InvalidMethodException
     */
    public function __call(string $name, array $arguments)
    {
        if ($name !== 'onlyTaxonomies' && is_callable([$this->queryBuilder, $name])) {
            $result = $this->queryBuilder->$name(...$arguments);

            try {
                return $this->resolveCallResult($result);
            } catch (InitializingModelWithWrongTaxonomyName|TermInstantiationError $exception) {
                throw new FailedToResolveCallResult($name, $arguments, $result, $exception);
            }
        }

        throw new InvalidMethodException($name, static::class);
    }

    /**
     * @param class-string|Taxonomy $model_class_namespace
     * @throws InvalidModelClass
     */
    public function __construct(private readonly string $model_class_namespace)
    {
        if (!is_a($this->model_class_namespace, $correct_class_namespace = Taxonomy::class, true)) {
            throw new InvalidModelClass($this->model_class_namespace, $correct_class_namespace);
        }

        $this->queryBuilder = TermQueryBuilder::make($this->model_class_namespace::getNameKey());
    }

    /**
     * @param callable $item_changes
     * @return Taxonomy[]
     * @throws FailedToUpdateTerms
     * @throws UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject
     */
    public function update(callable $item_changes): array
    {
        try {
            /** @var Taxonomy[] $terms */
            $terms = $this->get();

            Database::smartTransaction(function () use ($terms, $item_changes) {
                foreach ($terms as $term) {
                    $termUpdateBuilder = $item_changes($term);

                    if ($termUpdateBuilder instanceof UpdateBuilder) {
                        $termUpdateBuilder->update();
                        continue;
                    }

                    throw new UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
                }
            });

            return $terms;
        } catch (EmptyQueryBuilderArguments|QueryError $exception) {
            throw new FailedToUpdateTerms($exception);
        } catch (UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject $exception) {
            /** @noinspection PhpExceptionImmediatelyRethrownInspection */
            throw $exception;
        }
    }

    /**
     * @return Taxonomy[]
     * @throws DeleteTermError
     * @throws EmptyQueryBuilderArguments
     */
    public function delete(): array
    {
        /** @var Taxonomy[] $terms */
        $terms = $this->get();

        foreach ($terms as $term) {
            $term->delete();
        }

        return $terms;
    }

    public function toTermQueryBuilder(): TermQueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param bool|int|string|array|WP_Term|TermQueryBuilder|null $result
     * @return bool|int|string|array|Taxonomy|$this|null
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    private function resolveCallResult(
        bool|int|string|array|WP_Term|TermQueryBuilder|null $result
    ): bool|int|string|array|Taxonomy|static|null
    {
        if ($result instanceof TermQueryBuilder) {
            return $this;
        }

        if ($result instanceof WP_Term) {
            return $this->model_class_namespace::make($result);
        }

        if (is_array($result)) {
            return $this->resolveCallArrayResult($result);
        }

        return $result;
    }

    /**
     * @param array $result
     * @return Taxonomy[]
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws TermInstantiationError
     */
    private function resolveCallArrayResult(array $result): array
    {
        $resolved_result = [];

        foreach ($result as $key => $item) {
            if (!($item instanceof WP_Term)) {
                return $result;
            }

            $resolved_result[$key] = $this->model_class_namespace::make($item);
        }

        return $resolved_result;
    }
}

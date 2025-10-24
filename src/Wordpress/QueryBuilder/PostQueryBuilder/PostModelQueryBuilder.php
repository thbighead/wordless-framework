<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\MultipleUpdateBuilder;
use WP_Post;

/**
 * @mixin PostQueryBuilder
 */
class PostModelQueryBuilder
{
    private PostQueryBuilder $queryBuilder;

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
     * @return array|bool|int|string|Post|$this|null
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidMethodException
     * @throws PostTypeNotRegistered
     */
    public function __call(string $name, array $arguments): array|bool|int|string|static|Post|null
    {
        if ($name !== 'onlyOfType' && $name !== 'whereType' && is_callable([$this->queryBuilder, $name])) {
            return $this->resolveCallResult($this->queryBuilder->$name(...$arguments));
        }

        throw new InvalidMethodException($name, static::class);
    }

    /**
     * @param class-string|Post $model_class_namespace
     * @throws InvalidModelClass
     */
    public function __construct(protected readonly string $model_class_namespace)
    {
        if (!is_a($this->model_class_namespace, $correct_class_namespace = Post::class, true)) {
            throw new InvalidModelClass($this->model_class_namespace, $correct_class_namespace);
        }

        $this->queryBuilder = PostQueryBuilder::make($this->model_class_namespace::postType());
    }

    /**
     * @return MultipleUpdateBuilder
     * @throws EmptyQueryBuilderArguments
     */
    public function buildEdit(): Builder
    {
        return new MultipleUpdateBuilder($this, $this->model_class_namespace::postType());
    }

    public function toPostQueryBuilder(): PostQueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param bool|int|string|array|WP_Post|PostQueryBuilder|null $result
     * @return bool|int|string|array|Post|$this|null
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    private function resolveCallResult(
        bool|int|string|array|WP_Post|PostQueryBuilder|null $result
    ): bool|int|string|array|Post|static|null
    {
        if ($result instanceof PostQueryBuilder) {
            return $this;
        }

        if ($result instanceof WP_Post) {
            return $this->model_class_namespace::make($result);
        }

        if (is_array($result)) {
            return $this->resolveCallArrayResult($result);
        }

        return $result;
    }

    /**
     * @param array $result
     * @return Post[]
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    private function resolveCallArrayResult(array $result): array
    {
        $resolved_result = [];

        foreach ($result as $key => $item) {
            if (!($item instanceof WP_Post)) {
                return $result;
            }

            $resolved_result[$key] = $this->model_class_namespace::make($item);
        }

        return $resolved_result;
    }
}

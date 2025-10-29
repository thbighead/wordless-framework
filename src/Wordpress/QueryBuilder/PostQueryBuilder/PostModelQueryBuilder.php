<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;
use Wordless\Wordpress\Models\Post\Contracts\BasePost\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;
use Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits\Delete\Exceptions\WpDeletePostFailed;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\QueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\FailedToUpdatePosts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
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
     * @return array|bool|int|BasePost|$this|null
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidMethodException
     * @throws PostTypeNotRegistered
     */
    public function __call(string $name, array $arguments): array|bool|int|static|BasePost|null
    {
        if ($name !== 'onlyOfType' && $name !== 'whereType' && is_callable([$this->queryBuilder, $name])) {
            return $this->resolveCallResult($this->queryBuilder->$name(...$arguments));
        }

        throw new InvalidMethodException($name, static::class);
    }

    /**
     * @param class-string|BasePost $model_class_namespace
     * @throws InvalidModelClass
     */
    public function __construct(protected readonly string $model_class_namespace)
    {
        if (!is_a($this->model_class_namespace, $correct_class_namespace = BasePost::class, true)) {
            throw new InvalidModelClass($this->model_class_namespace, $correct_class_namespace);
        }

        $this->queryBuilder = PostQueryBuilder::make($this->model_class_namespace::postType());
    }

    /**
     * @return BasePost[]
     * @throws EmptyQueryBuilderArguments
     * @throws WpDeletePostFailed
     */
    public function delete(): array
    {
        /** @var BasePost[] $posts */
        $posts = $this->get();

        foreach ($posts as $post) {
            $post->delete();
        }

        return $posts;
    }

    public function toPostQueryBuilder(): PostQueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return BasePost[]
     * @throws EmptyQueryBuilderArguments
     * @throws WpDeletePostFailed
     */
    public function trash(): array
    {
        /** @var BasePost[] $posts */
        $posts = $this->get();

        foreach ($posts as $post) {
            $post->trash();
        }

        return $posts;
    }

    /**
     * @param callable $item_changes
     * @param bool $firing_after_events
     * @return BasePost[]
     * @throws FailedToUpdatePosts
     * @throws UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject
     * @noinspection PhpExceptionImmediatelyRethrownInspection
     */
    public function update(callable $item_changes, bool $firing_after_events = true): array
    {
        try {
            /** @var BasePost[] $posts */
            $posts = $this->get();

            Database::smartTransaction(function () use ($posts, $item_changes, $firing_after_events) {
                foreach ($posts as $post) {
                    $postUpdateBuilder = $item_changes($post);

                    if ($postUpdateBuilder instanceof UpdateBuilder) {
                        $postUpdateBuilder->update($firing_after_events);
                        continue;
                    }

                    throw new UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
                }
            });

            return $posts;
        } catch (EmptyQueryBuilderArguments|QueryError $exception) {
            throw new FailedToUpdatePosts($exception);
        } catch (UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject $exception) {
            throw $exception;
        }
    }

    /**
     * @param bool|int|array|WP_Post|PostQueryBuilder|null $result
     * @return bool|int|array|BasePost|$this|null
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    private function resolveCallResult(
        bool|int|array|WP_Post|PostQueryBuilder|null $result
    ): bool|int|array|BasePost|static|null
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
     * @return BasePost[]
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

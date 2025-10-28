<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder;

use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Comment;
use Wordless\Wordpress\Models\Comment\Exceptions\InvalidPostModelNamespace;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder;
use Wordless\Wordpress\QueryBuilder\Exceptions\InvalidMethodException;
use WP_Comment;

/**
 * @mixin CommentQueryBuilder
 */
class CommentModelQueryBuilder
{
    private CommentQueryBuilder $queryBuilder;

    public static function make(string $from_post_model_class_namespace = Post::class): static
    {
        return new static($from_post_model_class_namespace);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return $this|array|bool|int|Comment|null
     * @throws InvalidMethodException
     * @throws InvalidPostModelNamespace
     */
    public function __call(string $name, array $arguments)
    {
        if (is_callable([$this->queryBuilder, $name])) {
            $result = $this->queryBuilder->$name(...$arguments);

            return $this->resolveCallResult($result);
        }

        throw new InvalidMethodException($name, static::class);
    }

    public function __construct(private readonly string $from_post_model_class_namespace = Post::class)
    {
        $this->queryBuilder = CommentQueryBuilder::make();
    }

    /**
     * @param callable $item_changes
     * @return Taxonomy[]
     * @throws FailedToUpdateTerms
     * @throws UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject
     * @noinspection PhpExceptionImmediatelyRethrownInspection
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

    public function toCommentQueryBuilder(): CommentQueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param bool|int|array|WP_Comment|CommentQueryBuilder|null $result
     * @return bool|int|array|Comment|$this|null
     * @throws InvalidPostModelNamespace
     */
    private function resolveCallResult(
        bool|int|array|WP_Comment|CommentQueryBuilder|null $result
    ): bool|int|array|Comment|static|null
    {
        if ($result instanceof CommentQueryBuilder) {
            return $this;
        }

        if ($result instanceof WP_Comment) {
            return Comment::make($result, $this->from_post_model_class_namespace);
        }

        if (is_array($result)) {
            return $this->resolveCallArrayResult($result);
        }

        return $result;
    }

    /**
     * @param array $result
     * @return Comment[]
     * @throws InvalidPostModelNamespace
     */
    private function resolveCallArrayResult(array $result): array
    {
        $resolved_result = [];

        foreach ($result as $key => $item) {
            if (!($item instanceof WP_Comment)) {
                return $result;
            }

            $resolved_result[$key] = Comment::make($item, $this->from_post_model_class_namespace);
        }

        return $resolved_result;
    }
}

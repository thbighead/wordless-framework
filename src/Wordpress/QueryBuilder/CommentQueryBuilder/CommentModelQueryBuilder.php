<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder;

use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Comment;
use Wordless\Wordpress\Models\Comment\Exceptions\InvalidPostModelNamespace;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\CommentModelQueryBuilder\Exceptions\FailedToUpdateComments;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\CommentModelQueryBuilder\Exceptions\RemoveCommentFailed;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\CommentModelQueryBuilder\Exceptions\UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Exceptions\TryingToOrderByMetaWithoutMetaQuery;
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
     * @return Comment[]
     * @throws FailedToUpdateComments
     * @throws UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject
     * @noinspection PhpExceptionImmediatelyRethrownInspection
     */
    public function update(callable $item_changes): array
    {
        try {
            /** @var Comment[] $comments */
            $comments = $this->get();

            Database::smartTransaction(function () use ($comments, $item_changes) {
                foreach ($comments as $comment) {
                    $commentUpdateBuilder = $item_changes($comment);

                    if ($commentUpdateBuilder instanceof UpdateBuilder) {
                        $commentUpdateBuilder->update();
                        continue;
                    }

                    throw new UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
                }
            });

            return $comments;
        } catch (EmptyQueryBuilderArguments|QueryError|TryingToOrderByMetaWithoutMetaQuery $exception) {
            throw new FailedToUpdateComments($exception);
        } catch (UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject $exception) {
            throw $exception;
        }
    }

    /**
     * @return Comment[]
     * @throws RemoveCommentFailed
     */
    public function delete(): array
    {
        try {
            /** @var Comment[] $comments */
            $comments = $this->get();

            Database::smartTransaction(function () use ($comments) {
                foreach ($comments as $comment) {
                    $comment->delete();
                }
            });

            return $comments;
        } catch (EmptyQueryBuilderArguments|TryingToOrderByMetaWithoutMetaQuery|QueryError $exception) {
            throw new RemoveCommentFailed(__METHOD__, $exception);
        }
    }

    public function toCommentQueryBuilder(): CommentQueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return Comment[]
     * @throws RemoveCommentFailed
     */
    public function trash(): array
    {
        try {
            /** @var Comment[] $comments */
            $comments = $this->get();

            Database::smartTransaction(function () use ($comments) {
                foreach ($comments as $comment) {
                    $comment->trash();
                }
            });

            return $comments;
        } catch (EmptyQueryBuilderArguments|TryingToOrderByMetaWithoutMetaQuery|QueryError $exception) {
            throw new RemoveCommentFailed(__METHOD__, $exception);
        }
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

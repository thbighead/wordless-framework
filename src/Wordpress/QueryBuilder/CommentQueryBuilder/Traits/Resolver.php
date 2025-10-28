<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Exceptions\TryingToOrderByMetaWithoutMetaQuery;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Traits\Pagination;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder;
use WP_Comment;

trait Resolver
{
    use Pagination;

    private bool $already_queried = false;

    /**
     * @param array $extra_arguments
     * @param bool $query_again
     * @return int
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function count(array $extra_arguments = [], bool $query_again = false): int
    {
        if (!$this->already_queried || $query_again || !empty($extra_arguments)) {
            $this->arguments[self::KEY_NO_FOUND_ROWS] = false;
            $this->arguments['count'] = true;

            return $this->query($extra_arguments);
        }

        return !($this->arguments[self::KEY_NO_FOUND_ROWS] ?? true)
            ? $this->getQuery()->found_comments
            : count($this->getQuery()->comments);
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * @param int $quantity
     * @return WP_Comment|WP_Comment[]|null
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function first(int $quantity = 1): WP_Comment|array|null
    {
        return Arr::first($this->get(), $quantity);
    }

    /**
     * @param array $extra_arguments
     * @return array<int, WP_Comment>
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function get(array $extra_arguments = []): array
    {
        $comments = [];

        foreach ($this->query($extra_arguments) as $comment) {
            /** @var WP_Comment $comment */
            $comments[$comment->comment_ID] = $comment;
        }

        return $comments;
    }

    /**
     * @param array $extra_arguments
     * @return int[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public function getIds(array $extra_arguments = []): array
    {
        return $this->setToReturnOnlyIds()->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return array<string, string|int|bool|array>
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $arguments = parent::buildArguments();

        $this->resolveDateSubQuery($arguments)
            ->resolveMetaSubQuery($arguments)
            ->resolveOrderBy($arguments)
            ->resolveExtraArguments($arguments, $extra_arguments);

        return $arguments;
    }

    /**
     * @param array $extra_arguments
     * @return WP_Comment[]|int[]|int
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    private function query(array $extra_arguments = []): array|int
    {
        $this->already_queried = true;

        return $this->getQuery()->query($this->buildArguments($extra_arguments));
    }

    /**
     * @param array $arguments
     * @return $this
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    private function resolveOrderBy(array $arguments): static
    {
        if (empty($arguments[self::KEY_ORDER_BY])) {
            return $this;
        }

        if ($this->ordering_by_meta && !isset($arguments[MetaSubQueryBuilder::ARGUMENT_KEY])) {
            throw new TryingToOrderByMetaWithoutMetaQuery($arguments);
        }

        return $this;
    }

    private function setToReturnOnlyIds(): static
    {
        $this->arguments['fields'] = 'ids';

        return $this;
    }
}

<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait ParentComment
{
    private const KEY_PARENT = 'parent';
    private const KEY_PARENT_IN = 'parent__in';
    private const KEY_PARENT_NOT_IN = 'parent__not_in';

    public function whereParentComment(int $comment_id): static
    {
        $this->arguments[self::KEY_PARENT] = $comment_id;

        unset($this->arguments[self::KEY_PARENT_IN], $this->arguments[self::KEY_PARENT_NOT_IN]);

        return $this;
    }

    public function whereParentCommentIn(int $comment_id, int ...$comment_ids): static
    {
        $this->arguments[self::KEY_PARENT_IN] = Arr::prepend($comment_ids, $comment_id);

        unset($this->arguments[self::KEY_PARENT], $this->arguments[self::KEY_PARENT_NOT_IN]);

        return $this;
    }

    public function whereParentCommentNotIn(int $comment_id, int ...$comment_ids): static
    {
        $this->arguments[self::KEY_PARENT_NOT_IN] = Arr::prepend($comment_ids, $comment_id);

        unset($this->arguments[self::KEY_PARENT], $this->arguments[self::KEY_PARENT_IN]);

        return $this;
    }
}

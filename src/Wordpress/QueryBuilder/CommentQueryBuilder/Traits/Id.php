<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Id
{
    public function includeEvenIfUnapprovedIds(int $id, int ...$ids): static
    {
        $ids = Arr::prepend($ids, $id);

        $this->arguments[self::KEY_INCLUDE_UNAPPROVED] = isset($this->arguments[self::KEY_INCLUDE_UNAPPROVED])
            ? array_merge($this->arguments[self::KEY_INCLUDE_UNAPPROVED], $ids)
            : $ids;

        return $this;
    }

    public function whereId(int $id): static
    {
        return $this->whereIdIn($id);
    }

    public function whereIdIn(int $id, int ...$ids): static
    {
        $this->arguments['comment__in'] = Arr::prepend($ids, $id);

        return $this;
    }

    public function whereIdNotIn(int $id, int ...$ids): static
    {
        $this->arguments['comment__not_in'] = Arr::prepend($ids, $id);

        return $this;
    }
}

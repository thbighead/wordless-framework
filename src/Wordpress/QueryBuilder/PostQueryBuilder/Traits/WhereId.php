<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

trait WhereId
{
    private const KEY_PAGE_ID = 'page_id';
    private const KEY_POST_ID = 'p';
    private const KEY_POST_IN = 'post__in';
    private const KEY_POST_NOT_IN = 'post__not_in';

    /**
     * @param int $id
     * @param int ...$ids
     * @return $this
     */
    public function whereId(int $id, int ...$ids): static
    {
        if (empty($ids)) {
            $this->arguments[self::KEY_PAGE_ID] = $this->arguments[self::KEY_POST_ID] = $id;

            return $this;
        }

        array_unshift($ids, $id);

        $this->arguments[self::KEY_POST_IN] = $ids;

        unset($this->arguments[self::KEY_PAGE_ID]);
        unset($this->arguments[self::KEY_POST_ID]);
        unset($this->arguments[self::KEY_POST_NOT_IN]);

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return $this
     */
    public function whereNotId(int $id, int ...$ids): static
    {
        array_unshift($ids, $id);

        $this->arguments[self::KEY_POST_NOT_IN] = $ids;

        unset($this->arguments[self::KEY_PAGE_ID]);
        unset($this->arguments[self::KEY_POST_ID]);
        unset($this->arguments[self::KEY_POST_IN]);

        return $this;
    }
}

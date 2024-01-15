<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Id
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

            unset($this->arguments[self::KEY_POST_IN]);
            unset($this->arguments[self::KEY_POST_NOT_IN]);

            return $this;
        }

        $this->arguments[self::KEY_POST_IN] = Arr::prepend($ids, $id);

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
        $this->arguments[self::KEY_POST_NOT_IN] = Arr::prepend($ids, $id);

        unset($this->arguments[self::KEY_PAGE_ID]);
        unset($this->arguments[self::KEY_POST_ID]);
        unset($this->arguments[self::KEY_POST_IN]);

        return $this;
    }
}

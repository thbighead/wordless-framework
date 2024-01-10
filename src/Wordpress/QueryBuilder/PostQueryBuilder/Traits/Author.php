<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Author
{
    final public const KEY_AUTHOR = 'author';

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereAuthorId(int|array $ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }

        $this->arguments[self::KEY_AUTHOR] = $ids;

        return $this;
    }

    /**
     * @param string $author_nice_name
     * @return PostQueryBuilder
     */
    public function whereAuthorNiceName(string $author_nice_name): PostQueryBuilder
    {
        $this->arguments['author_name'] = $author_nice_name;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereNotAuthorId(int|array $ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments['author__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_AUTHOR] = -$ids;

        return $this;
    }
}

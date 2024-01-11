<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Author
{
    final public const KEY_AUTHOR = 'author';

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereAuthorId(int $id, int ...$ids): PostQueryBuilder
    {
        $this->arguments[self::KEY_AUTHOR] = implode(',', array_merge([$id], $ids));

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
    public function whereNotAuthorId(int ...$ids): PostQueryBuilder
    {
        if (count($ids) > 1) {
            $this->arguments['author__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_AUTHOR] = -$ids[0];

        return $this;
    }
}

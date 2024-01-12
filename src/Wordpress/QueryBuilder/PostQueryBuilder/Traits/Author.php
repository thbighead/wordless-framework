<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Author
{
    private const KEY_AUTHOR = 'author';

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereAuthorId(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_AUTHOR] = empty($ids) ? $id : implode(',', array_merge([$id], $ids));

        return $this;
    }

    /**
     * @param string $author_nice_name
     * @return PostQueryBuilder
     */
    public function whereAuthorNiceName(string $author_nice_name): static
    {
        $this->arguments['author_name'] = $author_nice_name;

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereNotAuthorId(int $id, int ...$ids): static
    {
        if (!empty($ids)) {
            array_unshift($ids, $id);

            $this->arguments['author__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_AUTHOR] = -$id;

        return $this;
    }
}

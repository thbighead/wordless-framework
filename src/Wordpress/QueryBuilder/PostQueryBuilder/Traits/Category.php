<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Category
{
    final public const KEY_CATEGORY = 'cat';

    /**
     * @param int|int[] $ids
     * @param bool $and
     * @return PostQueryBuilder
     */
    public function whereCategoryId(int|array $ids, bool $and = false): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments[$and ? 'category__and' : 'category__in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = $ids;

        return $this;
    }

    /**
     * @param string|string[] $names
     * @param bool $and
     * @return PostQueryBuilder
     */
    public function whereCategoryName(string|array $names, bool $and = false): PostQueryBuilder
    {
        $this->arguments['category_name'] = is_array($names) ?
            implode($and ? '+' : ',', $names) : $names;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereNotCategoryId(int|array $ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments['category__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = -$ids;

        return $this;
    }
}

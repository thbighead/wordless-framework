<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Tag
{
    /**
     * @param int[] $ids
     * @return PostQueryBuilder
     */
    public function whereNotTagId(array $ids): PostQueryBuilder
    {
        $this->arguments['tag__not_in'] = $ids;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @param bool $and
     * @return PostQueryBuilder
     */
    public function whereTagId(int|array $ids, bool $and = false): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments[$and ? 'tag__and' : 'tag__in'] = $ids;

            return $this;
        }

        $this->arguments['tag_id'] = $ids;

        return $this;
    }

    /**
     * @param string|string[] $names
     * @param bool $and
     * @return PostQueryBuilder
     */
    public function whereTagName(string|array $names, bool $and = false): PostQueryBuilder
    {
        $this->arguments['tag'] = is_array($names) ?
            implode($and ? '+' : ',', $names) : $names;

        return $this;
    }
}

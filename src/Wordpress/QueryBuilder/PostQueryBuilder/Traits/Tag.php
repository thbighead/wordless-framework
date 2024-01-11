<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Tag
{
    private const KEY_TAG_ID = 'tag_id';
    private const KEY_TAG = 'tag';

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereNotTagId(int $id, int ...$ids): PostQueryBuilder
    {
        array_unshift($ids, $id);

        $this->arguments['tag__not_in'] = $ids;

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllTagId(int $id, int ...$ids): PostQueryBuilder
    {
        if (!empty($ids)) {
            array_unshift($ids, $id);

            $this->arguments['tag__and'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_TAG_ID] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyTagId(int $id, int ...$ids): PostQueryBuilder
    {
        if (!empty($ids)) {
            array_unshift($ids, $id);

            $this->arguments['tag__in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_TAG_ID] = $id;

        return $this;
    }

    /**
     * @param string $name
     * @param string ...$names
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllTagName(string $name, string ...$names): PostQueryBuilder
    {
        $this->arguments[self::KEY_TAG] = !empty($names) ?
            implode('+', array_merge([$name], $names)) :
            $name;

        return $this;
    }

    /**
     * @param string $name
     * @param string ...$names
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyTagName(string $name, string ...$names): PostQueryBuilder
    {
        $this->arguments[self::KEY_TAG] = !empty($names) ?
            implode(',', array_merge([$name], $names)) :
            $name;

        return $this;
    }
}

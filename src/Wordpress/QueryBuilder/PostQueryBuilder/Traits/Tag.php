<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Tag
{
    private const KEY_TAG_ID = 'tag_id';
    private const KEY_TAG_ID_AND = 'tag__and';
    private const KEY_TAG_ID_IN = 'tag__in';
    private const KEY_TAG_ID_NOT_IN = 'tag__not_in';
    private const KEY_TAG_SLUG = 'tag';

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereNotRelatesToAnyTagId(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_TAG_ID_NOT_IN] = Arr::prepend($ids, $id);

        unset($this->arguments[self::KEY_TAG_ID]);
        unset($this->arguments[self::KEY_TAG_ID_AND]);
        unset($this->arguments[self::KEY_TAG_ID_IN]);

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllTagId(int $id, int ...$ids): static
    {
        if (!empty($ids)) {
            $this->arguments[self::KEY_TAG_ID_AND] = Arr::prepend($ids, $id);

            unset($this->arguments[self::KEY_TAG_ID]);
            unset($this->arguments[self::KEY_TAG_ID_IN]);
            unset($this->arguments[self::KEY_TAG_ID_NOT_IN]);

            return $this;
        }

        $this->whereTagId($id);

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyTagId(int $id, int ...$ids): static
    {
        if (!empty($ids)) {
            $this->arguments[self::KEY_TAG_ID_IN] = Arr::prepend($ids, $id);

            unset($this->arguments[self::KEY_TAG_ID]);
            unset($this->arguments[self::KEY_TAG_ID_AND]);
            unset($this->arguments[self::KEY_TAG_ID_NOT_IN]);

            return $this;
        }

        $this->whereTagId($id);

        return $this;
    }

    /**
     * @param string $name
     * @param string ...$names
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllTagSlug(string $name, string ...$names): static
    {
        $names = Arr::prepend($names, $name);

        $this->arguments[self::KEY_TAG_SLUG] = implode('+', $names);

        return $this;
    }

    /**
     * @param string $name
     * @param string ...$names
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyTagSlug(string $name, string ...$names): static
    {
        $names = Arr::prepend($names, $name);

        $this->arguments[self::KEY_TAG_SLUG] = implode(',', $names);

        return $this;
    }

    public function whereTagId(int $id): static
    {
        $this->arguments[self::KEY_TAG_ID] = $id;

        unset($this->arguments[self::KEY_TAG_ID_AND]);
        unset($this->arguments[self::KEY_TAG_ID_IN]);
        unset($this->arguments[self::KEY_TAG_ID_NOT_IN]);

        return $this;
    }
}

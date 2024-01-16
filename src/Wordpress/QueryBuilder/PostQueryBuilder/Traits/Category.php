<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Category
{
    private const KEY_CATEGORY_ID = 'cat';
    private const KEY_CATEGORY_ID_AND = 'category__and';
    private const KEY_CATEGORY_ID_IN = 'category__in';
    private const KEY_CATEGORY_ID_NOT_IN = 'category__not_in';
    private const KEY_CATEGORY_SLUG = 'category_name';

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereNotRelatesToAnyCategoryId(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_CATEGORY_ID_NOT_IN] = Arr::prepend($ids, $id);

        unset($this->arguments[self::KEY_CATEGORY_ID]);
        unset($this->arguments[self::KEY_CATEGORY_ID_AND]);
        unset($this->arguments[self::KEY_CATEGORY_ID_IN]);

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereNotRelatesToAnyCategoryIdIncludingChildren(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_CATEGORY_ID] = '-' . implode(',-', Arr::prepend($ids, $id));

        unset($this->arguments[self::KEY_CATEGORY_ID_AND]);
        unset($this->arguments[self::KEY_CATEGORY_ID_NOT_IN]);
        unset($this->arguments[self::KEY_CATEGORY_ID_IN]);

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllCategoryId(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_CATEGORY_ID_AND] = Arr::prepend($ids, $id);

        unset($this->arguments[self::KEY_CATEGORY_ID]);
        unset($this->arguments[self::KEY_CATEGORY_ID_NOT_IN]);
        unset($this->arguments[self::KEY_CATEGORY_ID_IN]);

        return $this;
    }

    /**
     * @param string $slug
     * @param string ...$slugs
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllCategorySlugIncludingChildren(string $slug, string ...$slugs): static
    {
        $this->arguments[self::KEY_CATEGORY_SLUG] = implode('+', Arr::prepend($slugs, $slug));

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyCategoryId(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_CATEGORY_ID_IN] = Arr::prepend($ids, $id);

        unset($this->arguments[self::KEY_CATEGORY_ID]);
        unset($this->arguments[self::KEY_CATEGORY_ID_AND]);
        unset($this->arguments[self::KEY_CATEGORY_ID_NOT_IN]);

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyCategoryIdIncludingChildren(int $id, int ...$ids): static
    {
        $this->arguments[self::KEY_CATEGORY_ID] = implode(',', Arr::prepend($ids, $id));

        unset($this->arguments[self::KEY_CATEGORY_ID_AND]);
        unset($this->arguments[self::KEY_CATEGORY_ID_NOT_IN]);
        unset($this->arguments[self::KEY_CATEGORY_ID_IN]);

        return $this;
    }

    /**
     * @param string $slug
     * @param string ...$slugs
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyCategorySlugIncludingChildren(string $slug, string ...$slugs): static
    {
        $this->arguments[self::KEY_CATEGORY_SLUG] = implode(',', Arr::prepend($slugs, $slug));

        return $this;
    }
}

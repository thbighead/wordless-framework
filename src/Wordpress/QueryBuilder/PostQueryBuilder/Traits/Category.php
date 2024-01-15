<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Category
{
    private const KEY_CATEGORY = 'cat';
    private const KEY_CATEGORY_NAME = 'category_name';

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereNotCategoryId(int $id, int ...$ids): static
    {
        if (!empty($ids)) {
            $this->arguments['category__not_in'] = Arr::prepend($ids, $id);

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = -$id;

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllCategoryId(int $id, int ...$ids): static
    {
        if (!empty($ids)) {
            $this->arguments['category__and'] = Arr::prepend($ids, $id);

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = $id;

        return $this;
    }

    /**
     * @param string $name
     * @param string ...$names
     * @return PostQueryBuilder
     */
    public function whereRelatesToAllCategoryName(string $name, string ...$names): static
    {
        $this->arguments[self::KEY_CATEGORY_NAME] = !empty($names) ?
            implode('+', array_merge([$name], $names)) :
            $name;

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyCategoryId(int $id, int ...$ids): static
    {
        if (!empty($ids)) {
            $this->arguments['category__in'] = Arr::prepend($ids, $id);

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = $id;

        return $this;
    }

    /**
     * @param string $name
     * @param string ...$names
     * @return PostQueryBuilder
     */
    public function whereRelatesToAnyCategoryName(string $name, string ...$names): static
    {
        $this->arguments[self::KEY_CATEGORY_NAME] = !empty($names) ?
            implode(',', array_merge([$name], $names)) :
            $name;

        return $this;
    }
}

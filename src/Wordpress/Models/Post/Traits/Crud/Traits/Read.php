<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post\Enums\StandardStatus;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Read
{
    /**
     * @param int $quantity
     * @return static|static[]|null
     * @throws EmptyQueryBuilderArguments
     */
    public static function firstPublished(int $quantity = 1): static|array|null
    {
        $queryBuilder = new PostQueryBuilder(static::postType());

        $result = $queryBuilder->whereStatus(StandardStatus::publish)->first($quantity);

        if ($result === null) {
            return null;
        }

        if (!is_array($result)) {
            return new static($result);
        }

        $posts = [];

        foreach ($result as $wpPost) {
            $posts[] = new static($wpPost);
        }

        return $posts;
    }

    /**
     * @param bool $with_acfs
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     */
    public static function getAll(bool $with_acfs = true): array
    {
        $queryBuilder = new PostQueryBuilder(static::postType());
        $all = [];

        foreach ($queryBuilder->get() as $post) {
            $all[] = new static($post, $with_acfs);
        }

        return $all;
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     */
    public static function noneCreated(): bool
    {
        return count(static::getAll(false)) <= 1;
    }
}

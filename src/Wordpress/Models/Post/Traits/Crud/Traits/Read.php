<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Read
{
    /**
     * @param string $slug
     * @param bool $with_acfs
     * @return static|null
     */
    public static function findBySlug(string $slug, bool $with_acfs = true): ?static
    {
        $post = get_page_by_path($slug, OBJECT, static::TYPE_KEY);

        return $post === null ? null : new static($post, $with_acfs);
    }

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
     * @param bool $with_acfs
     * @return array<string, static>
     * @throws EmptyQueryBuilderArguments
     */
    public static function getAllKeyedBySlug(bool $with_acfs = true): array
    {
        $all = [];

        foreach (static::getAll($with_acfs) as $post) {
            $all[$post->post_name] = $post;
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

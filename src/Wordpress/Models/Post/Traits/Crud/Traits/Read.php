<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;

trait Read
{
    /**
     * @param string $slug
     * @return static|null
     * @throws EmptyQueryBuilderArguments
     */
    public static function findBySlug(string $slug): ?static
    {
        $post = self::query()->whereSlug($slug)->first();

        return $post === null ? null : new static($post);
    }

    /**
     * @param int $quantity
     * @return static|static[]|null
     * @throws EmptyQueryBuilderArguments
     */
    public static function firstPublished(int $quantity = 1): static|array|null
    {
        $result = self::query()->whereStatus(StandardStatus::publish)->first($quantity);

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
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     */
    public static function getAll(): array
    {
        $all = [];

        foreach (self::query()->get() as $post) {
            $all[] = new static($post);
        }

        return $all;
    }

    /**
     * @return array<string, static>
     * @throws EmptyQueryBuilderArguments
     */
    public static function getAllKeyedBySlug(): array
    {
        $all = [];

        foreach (static::getAll() as $post) {
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

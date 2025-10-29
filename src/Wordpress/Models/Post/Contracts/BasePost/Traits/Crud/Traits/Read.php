<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;

/**
 * @mixin Crud
 */
trait Read
{
    /**
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * @return array<string, static>
     * @throws EmptyQueryBuilderArguments
     */
    public static function allKeyedBySlug(): array
    {
        $all = [];

        foreach (static::all() as $post) {
            $all[$post->post_name] = $post;
        }

        return $all;
    }

    /**
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     */
    public static function onlyPublished(): array
    {
        return static::query()->whereStatus(StandardStatus::publish)->get();
    }

    /**
     * @param string $slug
     * @return static|null
     * @throws EmptyQueryBuilderArguments
     */
    public static function findBySlug(string $slug): ?static
    {
        /** @var static|null */
        return static::query()->whereSlug($slug)->first();
    }

    /**
     * @param int $quantity
     * @return static|static[]|null
     * @throws EmptyQueryBuilderArguments
     */
    public static function firstPublished(int $quantity = 1): static|array|null
    {
        return static::query()->whereStatus(StandardStatus::publish)->first($quantity);
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     */
    public static function noneCreated(): bool
    {
        return !static::query()->exists();
    }
}

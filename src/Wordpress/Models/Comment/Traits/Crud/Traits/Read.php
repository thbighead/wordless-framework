<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Comment\Traits\Crud;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver\Exceptions\TryingToOrderByMetaWithoutMetaQuery;
use WP_Post;
use WP_User;

/**
 * @mixin Crud
 */
trait Read
{
    /**
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * @param User|WP_User|int $author
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function fromAuthor(User|WP_User|int $author): array
    {
        return static::query()->whereAuthor($author)->get();
    }

    /**
     * @param User|WP_User|int $author
     * @param User|WP_User|int ...$authors
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function fromAuthors(User|WP_User|int $author, User|WP_User|int ...$authors): array
    {
        return static::query()->whereAuthorIn($author, ...$authors)->get();
    }

    /**
     * @param BasePost|WP_Post|int $post
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function fromPost(BasePost|WP_Post|int $post): array
    {
        return static::query()->wherePost($post)->get();
    }

    /**
     * @param int $author_id
     * @param int ...$author_ids
     * @return static[]
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function fromPosts(int $author_id, int ...$author_ids): array
    {
        return static::query()->wherePostIdIn($author_id, ...$author_ids)->get();
    }

    /**
     * @return array
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function fromRegisteredAuthors(): array
    {
        return static::query()->onlyRegisteredAuthors()->get();
    }

    /**
     * @return array
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function fromUnregisteredAuthors(): array
    {
        return static::query()->onlyUnregisteredAuthors()->get();
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     * @throws TryingToOrderByMetaWithoutMetaQuery
     */
    public static function noneCreated(): bool
    {
        return !static::query()->exists();
    }
}

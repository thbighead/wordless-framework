<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\PostStatus;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;

trait Post
{
    private const KEY_POST_AUTHOR = 'post_author';
    private const KEY_POST_AUTHOR_IN = 'post_author__in';
    private const KEY_POST_AUTHOR_NOT_IN = 'post_author__not_in';
    private const KEY_POST_ID = 'post_id';
    private const KEY_POST_IN = 'post__in';
    private const KEY_POST_NAME = 'post_name';
    private const KEY_POST_NOT_IN = 'post__not_in';
    private const KEY_POST_PARENT = 'post_parent';
    private const KEY_POST_STATUS = 'post_status';
    private const KEY_POST_TYPE = 'post_type';

    public function wherePost(int $post_id): static
    {
        $this->arguments[self::KEY_POST_ID] = $post_id;

        unset($this->arguments[self::KEY_POST_IN], $this->arguments[self::KEY_POST_NOT_IN]);

        return $this;
    }

    public function wherePostAuthor(int $author_id): static
    {
        $this->arguments[self::KEY_POST_AUTHOR] = $author_id;

        unset($this->arguments[self::KEY_POST_AUTHOR_IN], $this->arguments[self::KEY_POST_AUTHOR_NOT_IN]);

        return $this;
    }

    public function wherePostAuthorIn(int $author_id, int ...$author_ids): static
    {
        $this->arguments[self::KEY_POST_AUTHOR_IN] = Arr::prepend($author_ids, $author_id);

        unset($this->arguments[self::KEY_POST_AUTHOR], $this->arguments[self::KEY_POST_AUTHOR_NOT_IN]);

        return $this;
    }

    public function wherePostAuthorNotIn(int $author_id, int ...$author_ids): static
    {
        $this->arguments[self::KEY_POST_AUTHOR_NOT_IN] = Arr::prepend($author_ids, $author_id);

        unset($this->arguments[self::KEY_POST_AUTHOR], $this->arguments[self::KEY_POST_AUTHOR_IN]);

        return $this;
    }

    public function wherePostIn(int $post_id, int ...$post_ids): static
    {
        $this->arguments[self::KEY_POST_IN] = Arr::prepend($post_ids, $post_id);

        unset($this->arguments[self::KEY_POST_ID], $this->arguments[self::KEY_POST_NOT_IN]);

        return $this;
    }

    public function wherePostNotIn(int $post_id, int ...$post_ids): static
    {
        $this->arguments[self::KEY_POST_NOT_IN] = Arr::prepend($post_ids, $post_id);

        unset($this->arguments[self::KEY_POST_ID], $this->arguments[self::KEY_POST_IN]);

        return $this;
    }

    public function wherePostParent(int $post_parent_id): static
    {
        $this->arguments[self::KEY_POST_PARENT] = $post_parent_id;

        return $this;
    }

    public function wherePostSlug(string $post_slug): static
    {
        $this->arguments[self::KEY_POST_NAME] = $post_slug;

        return $this;
    }

    public function wherePostStatusIn(
        PostStatus|StandardStatus|string $post_status,
        PostStatus|StandardStatus|string ...$post_statuses
    ): static
    {
        /** @var PostStatus|StandardStatus|string $status */
        foreach (Arr::prepend($post_statuses, $post_status) as $status) {
            if (!is_string($status)) {
                $status = $status->value ?? $status->name;
            }

            $this->arguments[self::KEY_POST_STATUS][] = $status;
        }

        return $this;
    }

    public function wherePostTypeIn(
        PostType|StandardType|string $post_type,
        PostType|StandardType|string ...$post_types
    ): static
    {
        /** @var PostType|StandardType|string $type */
        foreach (Arr::prepend($post_types, $post_type) as $type) {
            if (!is_string($type)) {
                $type = $type->name;
            }

            $this->arguments[self::KEY_POST_TYPE][] = $type;
        }

        return $this;
    }
}

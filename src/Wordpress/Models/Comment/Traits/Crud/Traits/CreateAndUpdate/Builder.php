<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate;

use Wordless\Application\Libraries\Carbon\Carbon;
use Wordless\Wordpress\Models\Comment;
use Wordless\Wordpress\Models\Comment\Enums\StandardType;
use Wordless\Wordpress\Models\Comment\Enums\Status;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use WP_Comment;
use WP_Post;
use WP_User;

abstract class Builder
{
    private const KEY_COMMENT_AUTHOR = 'comment_author';
    private const KEY_COMMENT_AUTHOR_EMAIL = 'comment_author_email';
    private const KEY_COMMENT_AUTHOR_URL = 'comment_author_url';

    protected BasePost|WP_Post|int|null $post = null;
    private ?string $author_email = null;
    private ?string $author_ip = null;
    private ?string $author_name = null;
    private ?string $author_url = null;
    private ?string $content = null;
    private ?Carbon $date = null;
    private ?string $http_user_agent = null;
    private ?int $karma = null;
    private array $meta = [];
    private Comment|WP_Comment|int|null $parent = null;
    private ?Status $status = null;
    private StandardType|string|null $type = null;
    private User|WP_User|int|null $user = null;

    public function authorEmail(string $author_email): static
    {
        $this->author_email = $author_email;

        return $this;
    }

    public function authorIp(string $author_ip): static
    {
        $this->author_ip = $author_ip;

        return $this;
    }

    public function authorName(string $author_name): static
    {
        $this->author_name = $author_name;

        return $this;
    }

    public function authorUrl(string $author_url): static
    {
        $this->author_url = $author_url;

        return $this;
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function date(Carbon $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function httpUserAgent(string $http_user_agent): static
    {
        $this->http_user_agent = $http_user_agent;

        return $this;
    }

    public function karma(int $karma): static
    {
        $this->karma = $karma;

        return $this;
    }

    public function parent(Comment|WP_Comment|int $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function post(BasePost|WP_Post|int $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function status(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function type(StandardType|string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function user(User|WP_User|int $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function withMeta(string $meta_key, mixed $meta_value): static
    {
        $this->meta[$meta_key] = $meta_value;

        return $this;
    }

    public function withMetas(array $metas): static
    {
        foreach ($metas as $meta_key => $meta_value) {
            $this->withMeta($meta_key, $meta_value);
        }

        return $this;
    }

    protected function mountCommentArrayArguments(): array
    {
        $comment_array = [];

        $this->resolveAuthorData($comment_array);

        if (!is_null($this->content)) {
            $comment_array['comment_content'] = $this->content;
        }

        if (!is_null($this->date)) {
            $comment_array['comment_date'] = $this->date->toDateTimeString();
        }

        if (!is_null($this->http_user_agent)) {
            $comment_array['comment_agent'] = $this->http_user_agent;
        }

        if (!is_null($this->karma)) {
            $comment_array['comment_karma'] = $this->karma;
        }

        if (!empty($this->meta)) {
            $comment_array['comment_meta'] = $this->meta;
        }

        if (!is_null($this->parent)) {
            $comment_array['comment_parent'] = $this->parent->comment_ID ?? $this->parent;
        }

        if (!is_null($this->post)) {
            $comment_array['comment_post_ID'] = $this->post->ID ?? $this->post;
        }

        if (!is_null($this->status)) {
            $comment_array['comment_approved'] = $this->status->value;
        }

        if (!is_null($this->type)) {
            $comment_array['comment_type'] = $this->type->value ?? $this->type;
        }

        return $comment_array;
    }

    private function resolveAuthorData(array &$comment_array): void
    {
        if (!is_null($this->author_ip)) {
            $comment_array['comment_author_IP'] = $this->author_ip;
        }

        is_null($this->user)
            ? $this->resolveUnregisteredAuthorData($comment_array)
            : $this->resolveRegisteredAuthorData($comment_array);

    }

    private function resolveRegisteredAuthorData(array &$comment_array): void
    {
        if (!($this->user instanceof User)) {
            try {
                $this->user = new User($this->user);
            } catch (NoUserAuthenticated) {
            }
        }

        $comment_array[self::KEY_COMMENT_AUTHOR] = $this->user->display_name;
        $comment_array[self::KEY_COMMENT_AUTHOR_EMAIL] = $this->user->user_email;
        $comment_array[self::KEY_COMMENT_AUTHOR_URL] = $this->user->user_url;
    }

    private function resolveUnregisteredAuthorData(array &$comment_array): void
    {
        if (!is_null($this->author_name)) {
            $comment_array[self::KEY_COMMENT_AUTHOR] = $this->author_name;
        }

        if (!is_null($this->author_email)) {
            $comment_array[self::KEY_COMMENT_AUTHOR_EMAIL] = $this->author_email;
        }

        if (!is_null($this->author_url)) {
            $comment_array[self::KEY_COMMENT_AUTHOR_URL] = $this->author_url;
        }
    }
}

<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate;

use DateTimeInterface;
use InvalidArgumentException;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Listeners\DisableComments\Contracts\DisableCommentsActionListener;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder\Exceptions\WpInsertPostError;
use Wordless\Wordpress\Models\PostStatus;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\WordlessUser;
use WP_Error;
use WP_User;

abstract class Builder
{
    private int|null $author_id;
    private bool $accepts_comments;
    private ?string $content = null;
    private ?string $excerpt = null;
    /** @var array<string, string> $meta */
    private array $meta = [];
    private Post|int|null $parent_id = null;
    private ?string $password = null;
    private DateTimeInterface|string|null $publishing_date = null;
    private ?string $slug = null;
    private StandardStatus|PostStatus|null $status = null;

    public function __construct(
        protected readonly ?int                                  $id,
        private string                                           $title,
        private readonly StandardType|PostType|CustomPost|string $type
    )
    {
        $this->author_id = WordlessUser::make()->id();

        try {
            $this->accepts_comments = (bool)Config::wordpressAdmin(
                DisableCommentsActionListener::CONFIG_KEY_ENABLE_COMMENTS
            );
        } catch (EmptyConfigKey|PathNotFoundException) {
            $this->accepts_comments = false;
        }
    }

    public function author(User|WP_User|int|null $user): static
    {
        $this->author_id = is_int($user) || is_null($user) ? $user : $user->ID;

        return $this;
    }

    public function content(string $html_content): static
    {
        $this->content = $html_content;

        return $this;
    }

    public function date(DateTimeInterface|string $publishing_date): static
    {
        $this->publishing_date = $publishing_date;

        return $this;
    }

    public function disableComments(): static
    {
        $this->accepts_comments = false;

        return $this;
    }

    public function enableComments(): static
    {
        $this->accepts_comments = true;

        return $this;
    }

    public function excerpt(string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function parent(Post|int $parent): static
    {
        $this->parent_id = $parent;

        return $this;
    }

    public function password(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $slug
     * @return $this
     * @throws InvalidArgumentException
     */
    public function slug(string $slug): static
    {
        $this->slug = Str::slugCase($slug);

        return $this;
    }

    public function status(StandardStatus|PostStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function user(User|WP_User|int|null $user): static
    {
        return $this->author($user);
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

    protected function mountPostArrayArguments(): array
    {
        $post_array = [
            'post_title' => $this->title,
            'post_content' => $this->content ?? '',
            'post_type' => $this->getTypeString(),
            'comment_status' => $this->accepts_comments ? 'open' : 'closed',
        ];

        if ($this->id !== null) {
            $post_array['ID'] = $this->id;
        }

        if ($this->author_id !== null) {
            $post_array['post_author'] = $this->author_id;
        }

        if ($this->publishing_date !== null) {
            $post_array['post_date'] = is_string($this->publishing_date)
                ? $this->publishing_date
                : $this->publishing_date->format('Y-m-d H:i:s');
        }

        if ($this->excerpt !== null) {
            $post_array['post_excerpt'] = $this->excerpt;
        }

        if ($this->status !== null) {
            $post_array['post_status'] = $this->status->name;
        }

        if ($this->password !== null) {
            $post_array['post_password'] = $this->password;
        }

        if ($this->slug !== null) {
            $post_array['post_name'] = $this->slug;
        }

        if ($this->parent_id !== null) {
            $post_array['post_parent'] = is_int($this->parent_id) ? $this->parent_id : $this->parent_id->ID;
        }

        if (!empty($this->meta)) {
            $post_array['meta_input'] = $this->meta;
        }

        return $post_array;
    }

    /**
     * @param bool $firing_after_events
     * @return int
     * @throws WpInsertPostError
     */
    final protected function callWpInsertPost(bool $firing_after_events = true): int
    {
        if (($result = wp_insert_post(
                $this->mountPostArrayArguments(),
                true,
                $firing_after_events
            )) instanceof WP_Error) {
            throw new WpInsertPostError($result);
        }

        return $result;
    }

    private function getTypeString(): string
    {
        if (($type = $this->type) instanceof CustomPost) {
            $type = $this->type->getType();
        }

        if (!is_string($type)) {
            $type = $type->name;
        }

        return $type;
    }
}

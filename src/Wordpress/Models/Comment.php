<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Comment\Exceptions\InvalidPostModelNamespace;
use Wordless\Wordpress\Models\Comment\Traits\MixinWpComment;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Comment;

/**
 * @mixin WP_Comment
 */
class Comment implements IRelatedMetaData
{
    use MixinWpComment;
    use WithMetaData;

    private BasePost $post;
    /** @var WP_Comment[] $children */
    private array $children;

    public static function objectType(): ObjectType
    {
        return ObjectType::comment;
    }

    /**
     * @param WP_Comment|int $comment
     * @param class-string|BasePost $from_post_model_class_namespace
     * @throws InvalidPostModelNamespace
     */
    public function __construct(
        WP_Comment|int          $comment,
        private readonly string $from_post_model_class_namespace = Post::class
    )
    {
        if (!is_a($this->from_post_model_class_namespace, BasePost::class, true)) {
            throw new InvalidPostModelNamespace($comment, $this->from_post_model_class_namespace);
        }

        $this->wpComment = $comment instanceof WP_Comment ? $comment : WP_Comment::get_instance($comment);
    }

    public function child(int $comment_id): ?WP_Comment
    {
        return $this->children()[$comment_id] ?? null;
    }

    /**
     * @return WP_Comment[]
     */
    public function children(): array
    {
        if (isset($this->children)) {
            return $this->children;
        }

        return $this->children = $this->get_children();
    }

    /**
     * @return BasePost
     * @throws InvalidPostModelNamespace
     */
    public function getPost(): BasePost
    {
        try {
            return $this->post ?? $this->post = $this->from_post_model_class_namespace::make(
                (int)$this->comment_post_ID
            );
        } catch (InitializingModelWithWrongPostType|PostTypeNotRegistered $exception) {
            throw new InvalidPostModelNamespace(
                $this->asWpComment(),
                $this->from_post_model_class_namespace,
                $exception
            );
        }
    }

    public function id(): int
    {
        return (int)$this->comment_ID;
    }

    public function toArray(bool $with_from_post = true): array
    {
        $array = $this->asWpComment()->to_array();

        if ($with_from_post && isset($this->post)) {
            try {
                $array['from_post'] = $this->getPost()->to_array();
            } catch (InvalidPostModelNamespace) {
            }
        }

        return $array;
    }
}

<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Comment\Enums\StandardType;
use Wordless\Wordpress\Models\Comment\Enums\Status;
use Wordless\Wordpress\Models\Comment\Exceptions\InvalidPostModelNamespace;
use Wordless\Wordpress\Models\Comment\Traits\Children;
use Wordless\Wordpress\Models\Comment\Traits\Crud;
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
    use Children;
    use Crud;
    use MixinWpComment;
    use WithMetaData;

    private BasePost $post;
    private Status $status;
    private StandardType|string $type;

    /**
     * @param WP_Comment|int $comment
     * @param string $from_post_model_class_namespace
     * @return static
     * @throws InvalidPostModelNamespace
     */
    public static function make(
        WP_Comment|int $comment,
        string         $from_post_model_class_namespace = Post::class
    ): static
    {
        return new static($comment, $from_post_model_class_namespace);
    }

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

        if (!empty($this->children)) {
            foreach ($this->children as $key => $child) {
                $array['children'][$key] = $child->toArray(false);
            }
        }

        return $array;
    }

    public function status(): Status
    {
        return $this->status ?? $this->status = Status::from($this->comment_approved);
    }

    public function type(): StandardType|string
    {
        if (isset($this->type)) {
            return $this->type;
        }

        return $this->type = StandardType::tryFrom($this->comment_type) ?? $this->comment_type;
    }
}

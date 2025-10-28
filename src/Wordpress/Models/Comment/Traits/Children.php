<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits;

use Wordless\Wordpress\Models\Comment;
use Wordless\Wordpress\Models\Comment\Exceptions\InvalidPostModelNamespace;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\CommentModelQueryBuilder;

/**
 * @mixin Comment
 */
trait Children
{
    /** @var static[] $children */
    private array $children;

    public function addChild(): static
    {
        //TODO

        return $this;
    }

    /**
     * @param int $comment_id
     * @return $this|null
     * @throws InvalidPostModelNamespace
     */
    public function child(int $comment_id): ?static
    {
        return $this->children()[$comment_id] ?? null;
    }

    /**
     * @return static[]
     * @throws InvalidPostModelNamespace
     */
    public function children(): array
    {
        if (isset($this->children)) {
            return $this->children;
        }

        $children = [];

        foreach ($this->get_children() as $key => $child) {
            $children[$key] = new static($child, $this->from_post_model_class_namespace);
        }

        return $this->children = $children;
    }

    public function queryChildren(): CommentModelQueryBuilder
    {
        return CommentModelQueryBuilder::make()->whereParentComment($this->id())
            ->withChildren();
    }
}

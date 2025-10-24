<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\MultipleUpdateBuilder\Traits\MultipleUpdateBuilder\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Post;

class CannotUpdateMultiplePostsWithSameSlug extends InvalidArgumentException
{
    /**
     * @param Post[] $posts
     * @param string $slug
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly array  $posts,
        public readonly string $slug,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            'Slugs are unique. You cannot update all '
            . count($this->posts)
            . " post slugs to $this->slug",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

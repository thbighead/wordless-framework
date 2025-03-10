<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\FeaturedImage\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Post;

class FailedToGetPostFeaturedImage extends ErrorException
{
    public function __construct(public readonly Post $post, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to get an attachment as featured image of post with id {$this->post->id()}",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}

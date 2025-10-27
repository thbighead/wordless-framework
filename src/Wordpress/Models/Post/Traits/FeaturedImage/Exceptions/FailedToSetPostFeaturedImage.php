<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\FeaturedImage\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;

class FailedToSetPostFeaturedImage extends ErrorException
{
    public function __construct(
        public readonly BasePost $post,
        public readonly int      $supposed_featured_image_attachment_id,
        ?Throwable               $previous = null
    )
    {
        parent::__construct(
            "Failed to set an attachment with id $this->supposed_featured_image_attachment_id as featured image of post with id {$this->post->id()}",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}

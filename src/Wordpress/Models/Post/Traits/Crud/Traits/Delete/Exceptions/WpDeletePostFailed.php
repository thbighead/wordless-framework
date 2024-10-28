<?php

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Post;

class WpDeletePostFailed extends ErrorException
{
    public function __construct(public readonly WP_Post $post, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to delete post with ID {$this->post->ID}",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}

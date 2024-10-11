<?php

namespace Wordless\Wordpress\Models\Post\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Post;

class FailedToGetPermalink extends DomainException
{
    public function __construct(public readonly WP_Post $post, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to get the post {$this->post->post_name} (ID: {$this->post->ID}) permalink to calculate its url.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

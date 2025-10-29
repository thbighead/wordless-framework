<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Comment;

class InvalidPostModelNamespace extends InvalidArgumentException
{
    public function __construct(
        public readonly WP_Comment|int $comment,
        public readonly string         $from_post_model_class_namespace,
        ?Throwable                     $previous = null
    )
    {
        $comment_text = $this->comment->comment_ID ?? $this->comment;

        parent::__construct(
            "The post model class $this->from_post_model_class_namespace cannot be the comment's with id $comment_text owner.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

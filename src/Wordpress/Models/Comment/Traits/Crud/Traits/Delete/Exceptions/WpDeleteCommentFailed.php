<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\Delete\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Comment;

class WpDeleteCommentFailed extends ErrorException
{
    public function __construct(public readonly Comment $comment, public readonly bool $force, ?Throwable $previous = null)
    {
        $force_text = $this->force ? 'with' : 'without';

        parent::__construct(
            "Could not delete comment {$this->comment->id()} throughout wp_delete_comment function $force_text force mode.",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}

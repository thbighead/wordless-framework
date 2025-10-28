<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\CreateBuilder\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class WpInsertCommentFailed extends ErrorException
{
    public function __construct(public readonly array $arguments, ?Throwable $previous = null)
    {
        parent::__construct(
            'wp_insert_comment function failed with the given arguments.',
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}

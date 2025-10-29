<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\MixinWpComment\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Comment;

class InvalidAttribute extends InvalidArgumentException
{
    public function __construct(public readonly string $attribute, ?Throwable $previous = null)
    {
        parent::__construct(
            "The attribute $this->attribute is not a property of " . WP_Comment::class,
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

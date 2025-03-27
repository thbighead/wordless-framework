<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\PostType\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PostTypeNotRegistered extends InvalidArgumentException
{
    public function __construct(public readonly string $not_registered_post_type_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "There's no custom posts registered with the following key: $this->not_registered_post_type_key",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

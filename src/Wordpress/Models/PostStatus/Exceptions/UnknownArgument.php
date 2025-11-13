<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\PostStatus\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\PostStatus;

class UnknownArgument extends DomainException
{
    public function __construct(public readonly string $invalid_property_name, ?Throwable $previous = null)
    {
        parent::__construct(
            PostStatus::class . " has no property named '$this->invalid_property_name.'",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

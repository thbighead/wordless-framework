<?php

namespace Wordless\Infrastructure\Enums\MimeType\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Enums\MimeType;

class InvalidMimeType extends DomainException
{
    public function __construct(public readonly string $invalid_mime_type, ?Throwable $previous = null)
    {
        parent::__construct(
            "The mime type '$this->invalid_mime_type' is invalid because it doesn't match the following pattern: "
            . MimeType::VALIDATION_REGEX,
            ExceptionCode::logic_control->value,
            $previous
        );
    }
}

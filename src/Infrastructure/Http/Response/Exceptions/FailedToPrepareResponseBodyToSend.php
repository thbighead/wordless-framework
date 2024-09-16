<?php

namespace Wordless\Infrastructure\Http\Response\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToPrepareResponseBodyToSend extends DomainException
{
    public function __construct(public readonly mixed $invalid_body, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to prepare the response body to send. Probably it was not an array nor string.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

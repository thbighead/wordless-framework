<?php

namespace Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CannotCallOfFromSubject extends DomainException
{
    public function __construct(public readonly string $subject_namespace, ?Throwable $previous = null)
    {
        parent::__construct(
            "Trying to call method named of from $subject_namespace class.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Type\Traits\MimeType\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\User;

class MimeTypeNotAllowed extends DomainException
{
    public function __construct(public readonly string $forbidden_mime_type, ?Throwable $previous = null)
    {
        parent::__construct(
            "The mime type '$this->forbidden_mime_type' is not allowed by this Wordpress Application. Check out if the authenticated user have permission to work with it.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getAuthenticatedUser(): User
    {
        return new User;
    }
}

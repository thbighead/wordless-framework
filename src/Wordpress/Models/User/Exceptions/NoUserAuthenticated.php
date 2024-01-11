<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\User;

class NoUserAuthenticated extends DomainException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to retrieve the current authenticated user from ' . User::class,
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

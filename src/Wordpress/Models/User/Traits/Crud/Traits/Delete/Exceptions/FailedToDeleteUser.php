<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits\Delete\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\User;

class FailedToDeleteUser extends DomainException
{
    public function __construct(public readonly User $user, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to delete user with id {$this->user->id()}",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

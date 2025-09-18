<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\WordlessUser\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class TryingToDeleteWordlessUser extends ErrorException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Can\'t delete Wordless User.',
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}

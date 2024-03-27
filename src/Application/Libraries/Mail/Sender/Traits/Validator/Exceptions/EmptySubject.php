<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptySubject extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'A subject must be provided, but it was an empty string.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyReceiversList extends InvalidArgumentException
{
    public function __construct(public readonly array $supposed_empty_receivers, ?Throwable $previous = null)
    {
        parent::__construct(
            'No receivers set to send mail message.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

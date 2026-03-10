<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyArrayHasNoIndex extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Empty arrays has no indices, so there is not a last one to return.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

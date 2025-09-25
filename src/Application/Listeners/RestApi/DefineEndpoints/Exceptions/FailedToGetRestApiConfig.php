<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\RestApi\DefineEndpoints\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGetRestApiConfig extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to get RestAPI config.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\RestApi\Authentication\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRetrievePublicApiEndpoints extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed te retrieve configured Wordless public REST API endpoints.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

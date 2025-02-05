<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Security\Csp\Exceptions;

use ErrorException;
use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToSentCspHeadersFromBuilder extends ErrorException
{
     public function __construct(public readonly Exception $originalException, ?Throwable $previous = null)
     {
         parent::__construct(
             $originalException->getMessage(),
             ExceptionCode::development_error->value,
             previous: $previous
         );
     }
}

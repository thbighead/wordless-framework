<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToAdaptFromOriginalCarbonClass extends RuntimeException
{
    public function __construct(public readonly string $original_carbon_class_namespace, ?Throwable $previous = null)
    {
        parent::__construct(
            "An error occurred when trying to instantiate the adapted class from $this->original_carbon_class_namespace",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

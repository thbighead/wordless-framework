<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon\CarbonTimeZone\Exceptions;

use Carbon\CarbonTimeZone;
use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToInstantiateOriginalCarbonTimeZone extends RuntimeException
{
    public function __construct(public readonly CarbonTimeZone|string $timezone, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to construct ' . CarbonTimeZone::class . " object with timezone '$this->timezone'",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

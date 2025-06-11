<?php declare(strict_types=1);

namespace Wordless\Core\InternalCache\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGenerateInternalCacheFile extends RuntimeException
{
    public function __construct(readonly public ?string $internal_cacher_namespace, ?Throwable $previous = null)
    {
        parent::__construct(
            'Could not generate cache from ' . $this->internal_cacher_namespace . '.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

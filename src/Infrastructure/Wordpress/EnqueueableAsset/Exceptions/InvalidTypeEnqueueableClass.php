<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions;

use OutOfRangeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;

class InvalidTypeEnqueueableClass extends OutOfRangeException
{
    public function __construct(public readonly string $enqueueable_class_namespace, ?Throwable $previous = null)
    {
        parent::__construct(
            "$this->enqueueable_class_namespace seems not to be a " . EnqueueableAsset::class,
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

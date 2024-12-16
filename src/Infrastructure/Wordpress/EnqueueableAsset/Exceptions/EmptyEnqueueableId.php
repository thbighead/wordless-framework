<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyEnqueueableId extends InvalidArgumentException
{
    public function __construct(public readonly string $enqueueable_class_namespace, ?Throwable $previous = null)
    {
        parent::__construct(
            "$this->enqueueable_class_namespace must have a non-empty id.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

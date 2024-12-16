<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DuplicatedEnqueueableId extends DomainException
{
    public function __construct(
        public readonly string $type_enqueueable_class_namespace,
        public readonly string $id,
        public readonly string $enqueueable_class_namespace,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Class $this->type_enqueueable_class_namespace duplicates id $this->id on $this->enqueueable_class_namespace.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

<?php

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DuplicatedEnqueueableId extends DomainException
{
    public function __construct(
        private readonly string $enqueueableClass,
        private readonly string $id,
        private readonly string $enqueueableClassFound,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Class $this->enqueueableClass duplicates id $this->id of $this->enqueueableClassFound.",
            ExceptionCode::development_error->value,
            $previous
        );
    }

    public function getEnqueueableClass(): string
    {
        return $this->enqueueableClass;
    }

    public function getEnqueueableClassFound(): string
    {
        return $this->enqueueableClassFound;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

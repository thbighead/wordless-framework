<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;

class FailedToUpdateMetaData extends ErrorException
{
    public function __construct(
        public readonly IRelatedMetaData $object,
        public readonly string $meta_key,
        public readonly string $meta_value,
        public readonly string $if_value_is,
        ?Throwable $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, previous: $previous);
    }

    private function mountMessage(): string
    {
        $introduction = "Failed to update meta data values with key '$this->meta_key' to '$this->meta_value'";

        if (!empty($this->if_value_is)) {
            $introduction .= " if value is '$this->if_value_is'";
        }

        return "$introduction of "
            . $this->object::objectType()->name
            . " with ID {$this->object->id()}.";
    }
}

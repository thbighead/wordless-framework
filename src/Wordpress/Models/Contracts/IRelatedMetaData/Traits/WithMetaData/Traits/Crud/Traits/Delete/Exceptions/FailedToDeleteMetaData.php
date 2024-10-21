<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Delete\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;

class FailedToDeleteMetaData extends ErrorException
{
    public function __construct(
        public readonly IRelatedMetaData $object,
        public readonly string           $meta_key,
        public readonly mixed            $meta_value,
        ?Throwable                       $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, previous: $previous);
    }

    private function mountMessage(): string
    {
        $introduction = "Failed to delete meta data values with key '$this->meta_key'";

        if (!empty($this->meta_value)) {
            $introduction .= ' and '
                . (GetType::isStringable($this->meta_value)
                    ? "value '$this->meta_value'"
                    : 'a not stringable value (' . GetType::of($this->meta_value) . ')');
        }

        return "$introduction of " . $this->object::objectType()->name . " with ID {$this->object->id()}.";
    }
}

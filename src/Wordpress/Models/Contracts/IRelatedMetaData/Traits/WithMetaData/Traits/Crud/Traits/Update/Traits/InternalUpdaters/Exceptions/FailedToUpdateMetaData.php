<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;

class FailedToUpdateMetaData extends ErrorException
{
    public function __construct(
        public readonly IRelatedMetaData $object,
        public readonly string           $meta_key,
        public readonly mixed            $meta_value,
        public readonly mixed            $if_value_is,
        ?Throwable                       $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, previous: $previous);
    }

    private function mountMessage(): string
    {
        $introduction = "Failed to update meta data values with key '$this->meta_key' to "
            . (GetType::isStringable($this->meta_value)
                ? "'$this->meta_value'"
                : 'a not stringable value (' . GetType::of($this->meta_value) . ')');

        if (!empty($this->if_value_is)) {
            $introduction .= ' if value is '
                . (GetType::isStringable($this->if_value_is)
                    ? "'$this->if_value_is'"
                    : GetType::of($this->meta_value) . ' (not stringable)');
        }

        return "$introduction of "
            . $this->object::objectType()->name
            . " with ID {$this->object->id()}.";
    }
}

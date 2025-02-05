<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Create\Traits\InternalAdders\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;

class FailedToInsertMetaData extends ErrorException
{
    public function __construct(
        public readonly IRelatedMetaData $object,
        public readonly string           $meta_key,
        public readonly mixed            $meta_value,
        public readonly bool             $unique,
        ?Throwable                       $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, previous: $previous);
    }

    private function mountMessage(): string
    {
        $introduction = 'Failed to insert';

        if ($this->unique) {
            $introduction .= ' unique';
        }

        return "$introduction meta data '$this->meta_key' => "
            . (GetType::isStringable($this->meta_value)
                ? "'$this->meta_value'"
                : '[a not stringable value (' . GetType::of($this->meta_value) . ')]')
            . ' into '
            . $this->object::objectType()->name
            . " of ID {$this->object->id()}.";
    }
}

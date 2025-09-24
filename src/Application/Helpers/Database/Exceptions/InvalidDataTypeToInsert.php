<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Database\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidDataTypeToInsert extends InvalidArgumentException
{
    public function __construct(
        public readonly string $type,
        public readonly mixed  $datum_key,
        public readonly mixed  $datum_value,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Data value of key '$this->datum_key' is of type '$this->type', but it should be "
            . implode(', ', [
                GetType::DOUBLE,
                GetType::INTEGER,
                GetType::STRING,
            ]),
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

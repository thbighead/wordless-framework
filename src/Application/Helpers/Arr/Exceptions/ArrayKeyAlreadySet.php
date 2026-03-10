<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class ArrayKeyAlreadySet extends DomainException
{
    public function __construct(
        public readonly array $original_array,
        public readonly string|int $key_attempted,
        public readonly string $action,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Cannot $this->action. The key $this->key_attempted already is set in array.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

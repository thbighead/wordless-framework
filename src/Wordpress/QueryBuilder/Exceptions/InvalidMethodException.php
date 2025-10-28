<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\Exceptions;

use BadMethodCallException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidMethodException extends BadMethodCallException
{
    public function __construct(
        public readonly string $method_name,
        public readonly string $caller_class_namespace,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "The method $this->method_name is invalid for $this->caller_class_namespace.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

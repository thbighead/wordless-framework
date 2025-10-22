<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidModelClass extends InvalidArgumentException
{
    public function __construct(
        public readonly string $incorrect_model_class_namespace,
        public readonly string $correct_model_class_namespace,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "The given model class ($this->incorrect_model_class_namespace) should be $this->correct_model_class_namespace.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

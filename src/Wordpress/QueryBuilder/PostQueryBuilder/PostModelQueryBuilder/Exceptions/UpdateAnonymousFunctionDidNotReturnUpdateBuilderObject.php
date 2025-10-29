<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions;

use BadMethodCallException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;

class UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject extends BadMethodCallException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'The callable function parameter at update must return a ' . UpdateBuilder::class . ' object.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

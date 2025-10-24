<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\Exceptions;

use BadMethodCallException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update\UpdateBuilder;

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

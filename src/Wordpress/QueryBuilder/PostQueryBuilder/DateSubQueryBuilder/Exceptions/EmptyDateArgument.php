<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;

class EmptyDateArgument extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'No arguments provided to ' . DateSubQueryBuilder::class . ' method.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

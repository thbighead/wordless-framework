<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\DTO\DateDTO\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\DateSubQueryBuilder\DTO\DateDTO;

class EmptyArguments extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'No arguments provided to ' . DateDTO::class . 'object.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

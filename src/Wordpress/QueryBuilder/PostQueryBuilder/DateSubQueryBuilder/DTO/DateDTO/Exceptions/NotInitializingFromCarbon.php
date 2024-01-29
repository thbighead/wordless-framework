<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO\Exceptions;

use Carbon\Carbon;
use LogicException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder\DTO\DateDTO;

class NotInitializingFromCarbon extends LogicException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            DateDTO::class . ' initializing from a non ' . Carbon::class . ' parameter.',
            ExceptionCode::caught_internally->value,
            $previous
        );
    }
}

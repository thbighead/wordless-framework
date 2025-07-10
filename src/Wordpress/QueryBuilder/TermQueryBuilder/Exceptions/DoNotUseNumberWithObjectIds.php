<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DoNotUseNumberWithObjectIds extends InvalidArgumentException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'From Wordpress docs: Note that "number" may not return accurate results when coupled with "object_ids".',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

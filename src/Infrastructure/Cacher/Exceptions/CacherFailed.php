<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Cacher\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class CacherFailed extends RuntimeException
{
    public function __construct(readonly public array $array_to_cache, ?Throwable $previous = null)
    {
        parent::__construct(
            'Cacher class failed to create cache due to an error.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

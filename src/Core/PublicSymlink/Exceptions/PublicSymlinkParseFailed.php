<?php declare(strict_types=1);

namespace Wordless\Core\PublicSymlink\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Core\PublicSymlink;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PublicSymlinkParseFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to construct ' . PublicSymlink::class . ' object.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

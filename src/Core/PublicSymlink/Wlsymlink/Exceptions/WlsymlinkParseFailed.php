<?php declare(strict_types=1);

namespace Wordless\Core\PublicSymlink\Wlsymlink\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Core\PublicSymlink\Wlsymlink;
use Wordless\Infrastructure\Enums\ExceptionCode;

class WlsymlinkParseFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to construct ' . Wlsymlink::class . ' object.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

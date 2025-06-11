<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\MainPlugin\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToBootEnqueueables extends RuntimeException
{
    public function __construct(readonly public bool $on_admin, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to boot enqueuables' . ($this->on_admin ? ' on admin' : '') . '.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

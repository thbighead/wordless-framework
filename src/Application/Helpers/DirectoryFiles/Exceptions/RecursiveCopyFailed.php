<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class RecursiveCopyFailed extends RuntimeException
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly array $except,
        public readonly bool $secure_mode,
        ?Throwable $previous = null
    )
    {
        $secure_text = $this->secure_mode ? 'secure' : 'insecure';
        $exceptions_text = empty($this->except) ? 'without' : 'with';

        parent::__construct(
            "Failed to recursive copy from '$this->from' to '$this->to' in $secure_text mode $exceptions_text exceptions.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

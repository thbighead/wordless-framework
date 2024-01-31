<?php declare(strict_types=1);

namespace Wordless\Core\PublicSymlink\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidPublicSymlinkTargetWithExceptions extends ErrorException
{
    public function __construct(
        public readonly string $raw_target_relative_path,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "The relative path \"$this->raw_target_relative_path\" must be a directory to use exceptions.",
            ExceptionCode::development_error->value,
            previous: $previous
        );
    }
}

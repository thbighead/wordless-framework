<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Core\InternalCache;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToFindCachedKey extends InvalidArgumentException
{
    public function __construct(
        private readonly string $full_key_string,
        private readonly string $partial_key_which_failed,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed to retrieve '$this->full_key_string' key from "
            . InternalCache::INTERNAL_WORDLESS_CACHE_CONSTANT_NAME
            . " at '$this->partial_key_which_failed'.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getFullKeyString(): string
    {
        return $this->full_key_string;
    }

    public function getPartialKeyWhichFailed(): string
    {
        return $this->partial_key_which_failed;
    }
}

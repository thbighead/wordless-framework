<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCopyFile extends ErrorException
{
    public function __construct(
        readonly public string $from,
        readonly public string $to,
        readonly public bool   $secure_mode,
        ?Throwable             $previous = null
    )
    {
        $security_word_mode = $this->secure_mode ? 'secure' : 'insecure';

        parent::__construct(
            "Failed to copy from $this->from to $this->to in $security_word_mode mode.",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }
}

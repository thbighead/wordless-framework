<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Option\Exception;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToUpdateOption extends Exception
{
    public function __construct(
        public readonly string $option_key,
        public readonly mixed  $option_value,
        public readonly mixed  $autoload,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            "Failed to update option with key $this->option_key",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

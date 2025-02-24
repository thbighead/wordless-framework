<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Option\Exception;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToDeleteOption extends Exception
{
    public function __construct(public readonly string $option_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to delete option with key $this->option_key",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}

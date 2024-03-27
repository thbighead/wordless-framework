<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions;

use Exception;
use Throwable;

class TriedToSetFunctionWhenRemovingListener extends Exception
{
    public function __construct(private readonly string $hook, ?Throwable $previous = null)
    {
        parent::__construct(
            "Tried to add a function to remove from a $this->hook listener.",
            0,
            $previous
        );
    }
}

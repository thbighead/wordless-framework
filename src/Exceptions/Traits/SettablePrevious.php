<?php declare(strict_types=1);

namespace Wordless\Exceptions\Traits;

use Throwable;

trait SettablePrevious
{
    protected ?Throwable $previous;

    public function setPrevious(Throwable $previous): static
    {
        $this->previous = $previous;

        return $this;
    }
}

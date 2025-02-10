<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits;

use Wordless\Application\Helpers\Arr;

trait Internal
{
    private bool $associative;
    private int $size;

    public function __construct(array $subject)
    {
        parent::__construct($subject);
    }

    public function getOriginalSubject(): array
    {
        return parent::getOriginalSubject();
    }

    public function getSubject(): array
    {
        return parent::getSubject();
    }

    private function decrementSize(int $by = 1): static
    {
        if (isset($this->size)) {
            $this->size -= max(abs($by), 1);
        }

        return $this;
    }

    private function incrementSize(int $by = 1): static
    {
        if (isset($this->size)) {
            $this->size += max(abs($by), 1);
        }

        return $this;
    }

    private function recalculateAssociative(): static
    {
        if (isset($this->associative)) {
            $this->associative = Arr::isAssociative($this->subject);
        }

        return $this;
    }

    private function recalculateAssociativeAfterAddition(): static
    {
        if (isset($this->associative) && !$this->associative) {
            $this->associative = Arr::isAssociative($this->subject);
        }

        return $this;
    }

    private function resetAssociative(): static
    {
        unset($this->associative);

        return $this;
    }

    private function updateSize(): static
    {
        if (isset($this->size)) {
            $this->size = Arr::size($this->subject);
        }

        return $this;
    }
}

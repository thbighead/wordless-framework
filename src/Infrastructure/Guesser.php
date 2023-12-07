<?php

namespace Wordless\Infrastructure;

abstract class Guesser
{
    private bool $already_guessed = false;
    private mixed $guessed_value;

    abstract protected function guessValue(): mixed;

    public function getValue(): mixed
    {
        if (!$this->already_guessed) {
            $this->already_guessed = true;

            return $this->guessed_value = $this->guessValue();
        }

        return $this->guessed_value;
    }

    public function resetGuessedValue(): void
    {
        $this->already_guessed = false;
        unset($this->guessed_value);
    }
}

<?php

namespace Wordless\Abstractions\Guessers;

abstract class BaseGuesser
{
    private bool $already_guessed = false;
    /** @var mixed $guessed_value */
    private $guessed_value;

    /** @return mixed */
    abstract protected function guessValue();

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (!$this->already_guessed) {
            $this->already_guessed = true;

            return $this->guessed_value = $this->guessValue();
        }

        return $this->guessed_value;
    }

    public function resetGuessedValue()
    {
        $this->already_guessed = false;
        unset($this->guessed_value);
    }
}
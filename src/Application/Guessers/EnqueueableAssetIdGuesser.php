<?php

namespace Wordless\Application\Guessers;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guesser;

class EnqueueableAssetIdGuesser extends Guesser
{
    public function __construct(private string $class_name)
    {
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function guessValue(): string
    {
        return (string)Str::of($this->class_name)
            ->afterLast('\\')
            ->lower()
            ->slugCase();
    }
}

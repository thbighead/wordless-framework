<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Adapters\CustomPost;
use Wordless\Helpers\Str;

class CustomPostTypeKeyGuesser extends Guesser
{
    private string $class_name;

    public function __construct(string $class_name)
    {
        $this->class_name = Str::afterLast($class_name, '\\');
    }

    protected function guessValue(): string
    {
        return Str::truncate(
            Str::slugCase($this->class_name),
            CustomPost::POST_TYPE_KEY_MAX_LENGTH
        );
    }
}

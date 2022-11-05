<?php

namespace Wordless\Abstractions\Guessers;

use Wordless\Adapters\WordlessCustomPost;
use Wordless\Helpers\Str;

class CustomPostTypeKeyGuesser extends BaseGuesser
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
            WordlessCustomPost::POST_TYPE_KEY_MAX_LENGTH
        );
    }
}

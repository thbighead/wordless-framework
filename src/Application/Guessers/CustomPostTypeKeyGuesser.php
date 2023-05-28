<?php

namespace Wordless\Application\Guessers;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guesser;
use Wordless\Wordpress\Models\PostType;

class CustomPostTypeKeyGuesser extends Guesser
{
    private string $class_name;

    public function __construct(string $class_name)
    {
        $this->class_name = Str::afterLast($class_name, '\\');
    }

    protected function guessValue(): string
    {
        return Str::truncate(Str::slugCase($this->class_name), PostType::KEY_MAX_LENGTH);
    }
}

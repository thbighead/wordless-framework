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
        return Str::of($this->class_name)->slugCase()->truncate(PostType::KEY_MAX_LENGTH);
    }
}

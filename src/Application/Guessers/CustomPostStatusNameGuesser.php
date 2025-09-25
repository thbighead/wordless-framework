<?php declare(strict_types=1);

namespace Wordless\Application\Guessers;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guesser;

class CustomPostStatusNameGuesser extends Guesser
{
    private string $class_name;

    public function __construct(string $class_name)
    {
        $this->class_name = Str::afterLast($class_name, '\\');
    }

    protected function guessValue(): string
    {
        return Str::slugCase($this->class_name);
    }
}

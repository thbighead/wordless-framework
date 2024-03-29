<?php declare(strict_types=1);

namespace Wordless\Application\Guessers;

use InvalidArgumentException;
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

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function guessValue(): string
    {
        return (string)Str::of($this->class_name)->slugCase()->truncate(PostType::KEY_MAX_LENGTH);
    }
}

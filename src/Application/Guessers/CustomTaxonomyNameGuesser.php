<?php declare(strict_types=1);

namespace Wordless\Application\Guessers;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Guesser;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy;

class CustomTaxonomyNameGuesser extends Guesser
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
        return (string)Str::of($this->class_name)->slugCase()
            ->truncate(CustomTaxonomy::TAXONOMY_NAME_MAX_LENGTH);
    }
}

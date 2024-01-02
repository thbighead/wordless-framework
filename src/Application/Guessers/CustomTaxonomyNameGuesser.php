<?php

namespace Wordless\Application\Guessers;

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

    protected function guessValue(): string
    {
        return Str::of($this->class_name)->slugCase()->truncate(CustomTaxonomy::TAXONOMY_NAME_MAX_LENGTH);
    }
}

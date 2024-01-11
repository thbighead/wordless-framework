<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions;


use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class InitializingModelWithWrongTaxonomyName extends InvalidArgumentException
{
    public function __construct(
        public readonly Taxonomy $model,
        public readonly bool     $with_acfs,
        ?Throwable               $previous = null
    )
    {
        parent::__construct($this->mountMessage(), 0, $previous);
    }

    private function mountMessage(): string
    {
        return "Tried to initialize a {$this->model::class} "
            . ($this->with_acfs ? 'with' : 'without')
            . " ACFs from a taxonomy named '{$this->model->taxonomy}'";
    }
}

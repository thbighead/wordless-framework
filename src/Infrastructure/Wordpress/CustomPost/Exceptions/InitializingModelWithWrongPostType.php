<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Wordpress\CustomPost;

class InitializingModelWithWrongPostType extends InvalidArgumentException
{
    public function __construct(
        public readonly CustomPost $model,
        public readonly bool $with_acfs,
        ?Throwable $previous = null
    )
    {
        parent::__construct($this->mountMessage(), 0, $previous);
    }

    private function mountMessage(): string
    {
        return "Tried to initialize a {$this->model::class} "
            . ($this->with_acfs ? 'with' : 'without')
            . " ACFs from a post of type '{$this->model->asWpPost()->post_type}'";
    }
}

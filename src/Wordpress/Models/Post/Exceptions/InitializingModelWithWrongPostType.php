<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Wordpress\Models\Post;

class InitializingModelWithWrongPostType extends InvalidArgumentException
{
    public function __construct(
        public readonly Post $model,
        public readonly bool $with_acfs,
        ?Throwable           $previous = null
    )
    {
        parent::__construct($this->mountMessage(), 0, $previous);
    }

    private function mountMessage(): string
    {
        $model_class_namespace = $this->model::class;

        return "Tried to initialize a {$model_class_namespace} "
            . ($this->with_acfs ? 'with' : 'without')
            . " ACFs from a post of type '{$this->model->asWpPost()->post_type}'";
    }
}

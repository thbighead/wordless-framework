<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;

class InitializingModelWithWrongPostType extends InvalidArgumentException
{
    public function __construct(public readonly BasePost $model, ?Throwable $previous = null)
    {
        parent::__construct(
            "Tried to initialize a {$this->model::class} from a post of type '{$this->model->asWpPost()->post_type}'",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

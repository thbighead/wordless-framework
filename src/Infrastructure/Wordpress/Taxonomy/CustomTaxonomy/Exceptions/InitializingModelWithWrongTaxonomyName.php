<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions;


use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class InitializingModelWithWrongTaxonomyName extends InvalidArgumentException
{
    public function __construct(public readonly Taxonomy $model, ?Throwable $previous = null)
    {
        parent::__construct(
            "Tried to initialize a {$this->model::class} from a taxonomy named '{$this->model->taxonomy}'",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

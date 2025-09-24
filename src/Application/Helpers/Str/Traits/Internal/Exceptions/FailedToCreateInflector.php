<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits\Internal\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Application\Helpers\Str\Enums\Language;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateInflector extends RuntimeException
{
    public function __construct(readonly public ?Language $language, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to mount Inflector vendor class for '
            . ($this->language?->value ?? 'null')
            . ' language.',
            ExceptionCode::development_error->value, $previous
        );
    }
}

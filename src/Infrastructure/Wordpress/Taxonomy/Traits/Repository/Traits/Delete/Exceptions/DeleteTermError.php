<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Delete\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;

class DeleteTermError extends ErrorException
{
    public function __construct(public readonly WP_Error $error, ?Throwable $previous = null)
    {
        parent::__construct($this->mountMessage(), ExceptionCode::development_error->value, previous: $previous);
    }

    private function mountMessage(): string
    {
        return implode('. ', $this->error->get_error_messages());
    }
}

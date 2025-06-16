<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Component\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidTemplatePathing extends RuntimeException
{
    public function __construct(readonly public string $invalid_relative_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't resolve component template relative path '$this->invalid_relative_path'",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

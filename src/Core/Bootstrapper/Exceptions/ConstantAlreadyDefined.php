<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class ConstantAlreadyDefined extends DomainException
{
    public function __construct(readonly public string $constant_name, ?Throwable $previous = null)
    {
        parent::__construct(
            "Trying to bootstrap a constant named '$this->constant_name', but it's already defined.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

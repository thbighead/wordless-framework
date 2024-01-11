<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DuplicatedMenuId extends DomainException
{
    public function __construct(
        private readonly string $menuClass,
        private readonly string $id,
        private readonly string $menuClassFound,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Class $this->menuClass duplicates id $this->id of $this->menuClassFound.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}

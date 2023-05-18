<?php

namespace Wordless\Core\Bootstrapper\Exceptions;

use DomainException;
use Throwable;
use Wordless\Enums\ExceptionCode;

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

    /**
     * @return string
     */
    public function getMenuClass(): string
    {
        return $this->menuClass;
    }

    /**
     * @return string
     */
    public function getMenuClassFound(): string
    {
        return $this->menuClassFound;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}

<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class DuplicatedMenuId extends Exception
{
    private string $menuClass;
    private string $menuClassFound;
    private string $id;

    public function __construct(
        string    $menuClass,
        string    $id,
        string    $menuClassFound,
        Throwable $previous = null
    )
    {
        $this->id = $id;
        $this->menuClass = $menuClass;
        $this->menuClassFound = $menuClassFound;

        parent::__construct(
            "Class $this->menuClass duplicates id $this->id of $this->menuClassFound.",
            0,
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
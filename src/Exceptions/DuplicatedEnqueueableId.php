<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class DuplicatedEnqueueableId extends Exception
{
    private string $enqueueableClass;
    private string $enqueueableClassFound;
    private string $id;

    public function __construct(
        string    $enqueueableClass,
        string    $id,
        string    $enqueueableClassFound,
        Throwable $previous = null
    )
    {
        $this->id = $id;
        $this->enqueueableClass = $enqueueableClass;
        $this->enqueueableClassFound = $enqueueableClassFound;

        parent::__construct(
            "Class $this->enqueueableClass duplicates id $this->id of $this->enqueueableClassFound.",
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getEnqueueableClass(): string
    {
        return $this->enqueueableClass;
    }

    /**
     * @return string
     */
    public function getEnqueueableClassFound(): string
    {
        return $this->enqueueableClassFound;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}

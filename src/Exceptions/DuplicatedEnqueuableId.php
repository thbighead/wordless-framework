<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class DuplicatedEnqueuableId extends Exception
{
    private string $enqueuableClass;
    private string $enqueueableClassFound;
    private string $id;

    public function __construct(
        string    $enqueuableClass,
        string    $id,
        string    $enqueueableClassFound,
        Throwable $previous = null
    )
    {
        $this->id = $id;
        $this->enqueuableClass = $enqueuableClass;
        $this->enqueueableClassFound = $enqueueableClassFound;

        parent::__construct(
            "Class $this->enqueuableClass duplicates id $this->id of $this->enqueueableClassFound.",
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getEnqueuableClass(): string
    {
        return $this->enqueuableClass;
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
<?php

namespace Wordless\Application\Helpers\DirestoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateSymlink extends ErrorException
{
    private string $link_relative_path;
    private string $target_relative_path;
    private string $from_absolute_path;

    public function __construct(
        string    $link_relative_path,
        string    $target_relative_path,
        string    $from_absolute_path,
        Throwable $previous = null
    )
    {
        $this->link_relative_path = $link_relative_path;
        $this->target_relative_path = $target_relative_path;
        $this->from_absolute_path = $from_absolute_path;

        parent::__construct(
            "Failed to create $this->link_relative_path symlink pointing to $this->target_relative_path from $this->from_absolute_path",
            0,
            $previous
        );
    }

    public function getFromAbsolutePath(): string
    {
        return $this->from_absolute_path;
    }

    public function getLinkRelativePath(): string
    {
        return $this->link_relative_path;
    }

    public function getTargetRelativePath(): string
    {
        return $this->target_relative_path;
    }
}

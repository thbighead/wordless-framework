<?php

namespace Wordless\Application\Helpers\DirestoryFiles\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateSymlink extends ErrorException
{
    public function __construct(
        private readonly string $link_name,
        private readonly string $target_path,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed to create symbolic link named \"$this->link_name\" targeting \"$this->target_path\"",
            ExceptionCode::logic_control->value,
            previous: $previous
        );
    }

    public function getLinkName(): string
    {
        return $this->link_name;
    }

    public function getTargetPath(): string
    {
        return $this->target_path;
    }
}

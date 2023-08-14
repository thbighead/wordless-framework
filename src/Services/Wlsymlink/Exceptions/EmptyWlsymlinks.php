<?php

namespace Wordless\Services\Wlsymlink\Exceptions;

use Exception;
use Throwable;

class EmptyWlsymlinks extends Exception
{
    private string $wlsymlinks_absolute_filepath;
    private string $raw_wlsymlinks_content;

    public function __construct(
        string $wlsymlinks_absolute_filepath,
        string $raw_wlsymlinks_content,
        Throwable $previous = null
    )
    {
        $this->wlsymlinks_absolute_filepath = $wlsymlinks_absolute_filepath;
        $this->raw_wlsymlinks_content = $raw_wlsymlinks_content;

        parent::__construct(
            "The $this->wlsymlinks_absolute_filepath resulted in no relative paths; raw content:\n$this->raw_wlsymlinks_content",
            0,
            $previous
        );
    }

    public function getRawWlsymlinksContent(): string
    {
        return $this->raw_wlsymlinks_content;
    }

    public function getWlsymlinksAbsoluteFilepath(): string
    {
        return $this->wlsymlinks_absolute_filepath;
    }
}

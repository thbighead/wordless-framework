<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\EnqueueableAsset\EnqueueableStyle;

class InvalidMediaOption extends Exception
{
    private string $enqueueableStyleClass;
    private string $media;

    public function __construct(string $enqueueableStyleClass, string $media, Throwable $previous = null)
    {
        $this->enqueueableStyleClass = $enqueueableStyleClass;
        $this->media = $media;

        parent::__construct(
            "Class $this->enqueueableStyleClass has an invalid media type of \"$this->media\". The valid types are: "
            . implode(', ', EnqueueableStyle::MEDIA_OPTIONS),
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getEnqueueableStyleClass(): string
    {
        return $this->enqueueableStyleClass;
    }

    /**
     * @return string
     */
    public function getMedia(): string
    {
        return $this->media;
    }
}

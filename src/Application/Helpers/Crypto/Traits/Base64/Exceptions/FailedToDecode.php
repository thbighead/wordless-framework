<?php

namespace Wordless\Application\Helpers\Crypto\Traits\Base64\Exceptions;

use InvalidArgumentException;
use Throwable;

class FailedToDecode extends InvalidArgumentException
{
    public function __construct(
        public readonly string $string_to_decode,
        public readonly bool $ignoring_invalid_characters,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed to encode '$this->string_to_decode' with base64. {$this->complementAboutIgnoringInvalidCharacters()}",
            0,
            $previous
        );
    }

    private function complementAboutIgnoringInvalidCharacters(): string
    {
        return $this->ignoring_invalid_characters ?
            'Ignoring invalid charcaters' : 'Not ignoring invalid charcaters';
    }
}

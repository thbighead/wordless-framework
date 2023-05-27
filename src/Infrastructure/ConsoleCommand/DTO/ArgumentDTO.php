<?php

namespace Wordless\Infrastructure\ConsoleCommand\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO\Enums\ArgumentMode;

class ArgumentDTO
{
    public function __construct(
        public string      $name,
        public string      $description,
        public ?ArgumentMode $mode = null,
        public mixed       $default = null
    )
    {
    }
}

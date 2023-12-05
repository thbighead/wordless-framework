<?php

namespace Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;

readonly class ArgumentDTO extends InputDTO
{
    public function __construct(
        string        $name,
        string        $description,
        ?ArgumentMode $mode = null,
        mixed         $default = null
    )
    {
        parent::__construct($name, $description, $mode, $default);
    }
}

<?php

namespace Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

readonly class OptionDTO extends InputDTO
{
    /**
     * @param string $name
     * @param string[]|string|null $shortcut
     * @param string $description
     * @param OptionMode|null $mode
     * @param mixed|null $default
     */
    public function __construct(
        string                   $name,
        string                   $description,
        public array|string|null $shortcut = null,
        ?OptionMode              $mode = null,
        mixed                    $default = null
    )
    {
        parent::__construct($name, $description, $mode, $default);
    }
}

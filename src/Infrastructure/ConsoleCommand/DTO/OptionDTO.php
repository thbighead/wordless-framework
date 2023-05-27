<?php

namespace Wordless\Infrastructure\ConsoleCommand\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO\Enums\OptionMode;

class OptionDTO
{
    /**
     * @param string $name
     * @param string[]|string|null $shortcut
     * @param string $description
     * @param OptionMode|null $mode
     * @param mixed|null $default
     */
    public function __construct(
        public string            $name,
        public string            $description,
        public array|string|null $shortcut = null,
        public ?OptionMode       $mode = null,
        public mixed             $default = null
    )
    {
        if ($this->mode === OptionMode::optional_value && $this->default === null) {
            $this->default = false;
        }
    }
}

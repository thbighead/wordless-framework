<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;

readonly class ArgumentDTO extends InputDTO
{
    public static function make(
        string        $name,
        string        $description,
        ?ArgumentMode $mode = null,
        mixed         $default = null
    ): static
    {
        return new static($name, $description, $mode, $default);
    }

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

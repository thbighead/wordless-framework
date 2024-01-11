<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

abstract readonly class InputDTO
{
    public function __construct(
        public string                       $name,
        public string                       $description,
        public ArgumentMode|OptionMode|null $mode = null,
        public mixed                        $default = null
    )
    {
    }
}

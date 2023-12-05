<?php

namespace Wordless\Application\Commands\Traits\ForceMode\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

class ForceModeOptionDTO extends OptionDTO
{
    final public const FORCE_MODE = 'force';

    public function __construct(string $description)
    {
        parent::__construct(self::FORCE_MODE, $description, 'f', OptionMode::no_value);
    }
}

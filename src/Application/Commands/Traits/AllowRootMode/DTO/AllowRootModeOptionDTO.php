<?php

namespace Wordless\Application\Commands\Traits\AllowRootMode\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO\Enums\OptionMode;

class AllowRootModeOptionDTO extends OptionDTO
{
    final public const ALLOW_ROOT_MODE = 'allow-root';

    public function __construct(string $description)
    {
        parent::__construct(self::ALLOW_ROOT_MODE, $description, mode: OptionMode::no_value);
    }
}

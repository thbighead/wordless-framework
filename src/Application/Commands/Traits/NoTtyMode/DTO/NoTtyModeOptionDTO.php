<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits\NoTtyMode\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

readonly class NoTtyModeOptionDTO extends OptionDTO
{
    final public const NO_TTY_MODE = 'no-tty';

    public function __construct(string $description)
    {
        parent::__construct(self::NO_TTY_MODE, $description, mode: OptionMode::no_value);
    }
}

<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums;

use Wordless\Application\Libraries\TypeBackedEnum\StringBackedEnum;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration\Contracts\IDecoration;

enum Decoration: string implements IDecoration, StringBackedEnum
{
    case comment = 'gray';
    case danger = 'red';
    case info = 'cyan';
    case success = 'bright-green';
    case warning = 'yellow';

    public function color(): string
    {
        return $this->value;
    }

    public function name(): string
    {
        return $this->name;
    }
}

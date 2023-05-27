<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration\Contracts\IDecoration;

enum Decoration: string implements IDecoration
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

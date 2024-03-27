<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration\Contracts;

interface IDecoration
{
    public function color(): string;

    public function name(): string;
}

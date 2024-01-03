<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Enums\Decoration\Contracts\IDecoration;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits\Comment;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits\Danger;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits\Info;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits\Setup;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits\Success;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\DecoratedMessage\Traits\Warning;

trait DecoratedMessage
{
    use Comment;
    use Danger;
    use Info;
    use Setup;
    use Success;
    use Warning;

    protected function decorateText(string $text, ?IDecoration $decoration = null): string
    {
        if ($decoration !== null) {
            return "<{$decoration->name()}>$text</>";
        }

        return $text;
    }
}

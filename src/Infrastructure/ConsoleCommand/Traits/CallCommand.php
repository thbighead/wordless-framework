<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal;

trait CallCommand
{
    use External;
    use Internal;
}

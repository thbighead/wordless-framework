<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal;

trait CallCommand
{
    use External;
    use Internal;
}

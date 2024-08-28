<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

abstract class GlobalEnqueueableStyle extends EnqueueableStyle
{
    final public function loadOnAdmin(): bool
    {
        return true;
    }

    final public function loadOnFrontend(): bool
    {
        return true;
    }
}

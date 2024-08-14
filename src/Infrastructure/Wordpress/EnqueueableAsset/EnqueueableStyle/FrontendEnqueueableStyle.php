<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

abstract class FrontendEnqueueableStyle extends EnqueueableStyle
{
    final public function loadOnAdmin(): bool
    {
        return false;
    }

    final public function loadOnFrontend(): bool
    {
        return true;
    }
}

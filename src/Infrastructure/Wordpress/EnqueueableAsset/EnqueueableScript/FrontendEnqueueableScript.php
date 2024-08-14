<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;

abstract class FrontendEnqueueableScript extends EnqueueableScript
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

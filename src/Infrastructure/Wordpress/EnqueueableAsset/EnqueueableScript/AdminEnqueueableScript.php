<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;

abstract class AdminEnqueueableScript extends EnqueueableScript
{
    final public function loadOnAdmin(): bool
    {
        return true;
    }

    final public function loadOnFrontend(): bool
    {
        return false;
    }
}
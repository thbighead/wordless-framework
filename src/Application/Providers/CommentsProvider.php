<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\DisableComments\DisableCptComments;
use Wordless\Application\Listeners\DisableComments\DisableDefaultComments;
use Wordless\Infrastructure\Provider;

class CommentsProvider extends Provider
{
    public function registerListeners(): array
    {
        return [
            DisableCptComments::class,
            DisableDefaultComments::class,
        ];
    }
}

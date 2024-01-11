<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\DisableCptComments;
use Wordless\Application\Listeners\DisableDefaultComments;
use Wordless\Infrastructure\Provider;

final class CommentsProvider extends Provider
{
    public function registerListeners(): array
    {
        return [
            DisableCptComments::class,
            DisableDefaultComments::class,
        ];
    }
}

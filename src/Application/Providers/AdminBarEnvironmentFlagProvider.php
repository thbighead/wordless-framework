<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\EnvironmentOnAdminBar;
use Wordless\Application\Styles\AdminBarEnvironmentFlagStyle;
use Wordless\Infrastructure\Provider;

class AdminBarEnvironmentFlagProvider extends Provider
{
//    public function registerEnqueueableStyles(): array
//    {
//        return [
//            AdminBarEnvironmentFlagStyle::class,
//        ];
//    }

    public function registerListeners(): array
    {
        return [
            EnvironmentOnAdminBar::class,
        ];
    }
}

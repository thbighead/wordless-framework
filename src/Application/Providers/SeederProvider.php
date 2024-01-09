<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Seeder\CreateDummyCategories;
use Wordless\Application\Commands\Seeder\CreateDummyPosts;
use Wordless\Infrastructure\Provider;

final class SeederProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            CreateDummyCategories::class,
            CreateDummyPosts::class,
        ];
    }
}

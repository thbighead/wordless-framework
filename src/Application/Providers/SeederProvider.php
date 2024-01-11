<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Seeder\CreateDummyCategories;
use Wordless\Application\Commands\Seeder\CreateDummyComments;
use Wordless\Application\Commands\Seeder\CreateDummyPosts;
use Wordless\Application\Commands\Seeder\CreateDummyTags;
use Wordless\Application\Commands\Seeder\CreateDummyTaxonomyTerm;
use Wordless\Infrastructure\Provider;

final class SeederProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            CreateDummyCategories::class,
            CreateDummyComments::class,
            CreateDummyPosts::class,
            CreateDummyTags::class,
            CreateDummyTaxonomyTerm::class,
        ];
    }
}

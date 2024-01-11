<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Seeders\CreateDummyCategories;
use Wordless\Application\Commands\Seeders\CreateDummyComments;
use Wordless\Application\Commands\Seeders\CreateDummyPosts;
use Wordless\Application\Commands\Seeders\CreateDummyTags;
use Wordless\Application\Commands\Seeders\CreateDummyTaxonomyTerms;
use Wordless\Infrastructure\Provider;

final class SeedersProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            CreateDummyCategories::class,
            CreateDummyComments::class,
            CreateDummyPosts::class,
            CreateDummyTags::class,
            CreateDummyTaxonomyTerms::class,
        ];
    }
}

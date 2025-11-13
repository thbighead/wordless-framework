<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Seeders\CommentsSeeder;
use Wordless\Application\Commands\Seeders\PostsSeeder;
use Wordless\Application\Commands\Seeders\Seed;
use Wordless\Application\Commands\Seeders\TaxonomyTermsSeeder;
use Wordless\Application\Commands\Seeders\UsersSeeder;
use Wordless\Infrastructure\Provider;

class SeedersProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            CommentsSeeder::class,
            PostsSeeder::class,
            Seed::class,
            TaxonomyTermsSeeder::class,
            UsersSeeder::class,
        ];
    }
}

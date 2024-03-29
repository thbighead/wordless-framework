<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Migrations\Migrate;
use Wordless\Application\Commands\Migrations\Migrate\FlushMigrations;
use Wordless\Application\Commands\Migrations\Migrate\MigrateRollback;
use Wordless\Application\Commands\Migrations\MigrationList;
use Wordless\Infrastructure\Provider;

class MigrationsProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            FlushMigrations::class,
            Migrate::class,
            MigrateRollback::class,
            MigrationList::class,
        ];
    }
}

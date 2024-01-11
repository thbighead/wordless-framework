<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Makers\MakeCommand;
use Wordless\Application\Commands\Makers\MakeController;
use Wordless\Application\Commands\Makers\MakeCustomPostType;
use Wordless\Application\Commands\Makers\MakeListener;
use Wordless\Application\Commands\Makers\MakeMigration;
use Wordless\Infrastructure\Provider;

final class MakersProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            MakeCommand::class,
            MakeController::class,
            MakeCustomPostType::class,
            MakeListener::class,
            MakeMigration::class,
        ];
    }
}

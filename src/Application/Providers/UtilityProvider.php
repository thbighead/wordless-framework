<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\Diagnostics;
use Wordless\Application\Commands\HelixShell;
use Wordless\Application\Commands\Utility\DatabaseOverwrite;
use Wordless\Application\Commands\Utility\ReplaceBaseUrls;
use Wordless\Application\Commands\Utility\RunTests;
use Wordless\Application\Commands\Utility\WpHooksList;
use Wordless\Infrastructure\Provider;

class UtilityProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            DatabaseOverwrite::class,
            Diagnostics::class,
            ReplaceBaseUrls::class,
            RunTests::class,
            HelixShell::class,
            WpHooksList::class,
        ];
    }

    public function registerProviders(): array
    {
        return [
            SvgAdminUploadProvider::class,
        ];
    }
}

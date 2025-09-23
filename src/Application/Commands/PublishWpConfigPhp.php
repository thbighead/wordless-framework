<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Mounters\Stub\WpConfigStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class PublishWpConfigPhp extends ConsoleCommand
{
    final public const COMMAND_NAME = 'publish:wp-config.php';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Publishes wp-config.php file inside wp-core directory.';
    }

    protected function help(): string
    {
        return 'Copy wp-config.php file from project stubs inside wp-core directory even if it already exists.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            $wp_config_destiny_path = ProjectPath::wpCore() . '/wp-config.php';

            if (Environment::isFramework()) {
                DirectoryFiles::copyFile(
                    ProjectPath::root('wp-config.php'),
                    $wp_config_destiny_path,
                    false
                );

                return Command::SUCCESS;
            }

            WpConfigStubMounter::make($wp_config_destiny_path)->mountNewFile();
        } catch (FailedToCopyFile|FailedToCopyStub|PathNotFoundException $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }

        return Command::SUCCESS;
    }
}

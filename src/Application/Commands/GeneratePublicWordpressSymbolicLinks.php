<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks\Exceptions\FailedToCreatePublicSymlink;
use Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks\Exceptions\FailedToEnsureSymlinkDirectoryHierarchyAtPublic;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateSymlink;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToTravelDirectories;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\PublicSymlink;
use Wordless\Core\PublicSymlink\Exceptions\PublicSymlinkParseFailed;
use Wordless\Core\PublicSymlink\Resolver;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class GeneratePublicWordpressSymbolicLinks extends ConsoleCommand
{
    final public const COMMAND_NAME = 'wordless:symlinks';
    final public const PUBLIC_SYMLINK_KEY = 'public-symlinks';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Generate public symlinks to WordPress files.';
    }

    protected function help(): string
    {
        return 'Reading config/wordless.php public-symlinks key this script creates symbolic links to allow or not direct access through HTTP.';
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
            $this->wrapScriptWithMessages('Removing old symlinks...', function () {
                DirectoryFiles::recursiveRemoveSymbolicLinks(ProjectPath::public());
                DirectoryFiles::recursiveDeleteEmptyDirectories(ProjectPath::public(), false);
            });

            $this->wrapScriptWithMessages('Generating public symbolic links...', function () {
                $symlinks_configured = Config::wordless(self::PUBLIC_SYMLINK_KEY, []);
                $publicSymlinkResolver = new Resolver;

                foreach ($symlinks_configured as $link_relative_path => $target_relative_path) {
                    $publicSymlinkResolver->addSymlink(new PublicSymlink($link_relative_path, $target_relative_path));
                }

                foreach ($publicSymlinkResolver->retrieveCleanedSymlinks() as $link_relative_path_from_public => $target_relative_path_from_public) {
                    $this->createPublicSymlink($link_relative_path_from_public, $target_relative_path_from_public);
                }
            });
        } catch (FailedToCreatePublicSymlink
        |FailedToDeletePath
        |InvalidDirectory
        |PathNotFoundException
        |PublicSymlinkParseFailed $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $link_relative_path
     * @param string $target_relative_path
     * @return void
     * @throws FailedToCreatePublicSymlink
     */
    private function createPublicSymlink(string $link_relative_path, string $target_relative_path): void
    {
        $this->writelnInfoWhenVerbose(
            "Creating \"$link_relative_path\" from public pointing to \"$target_relative_path\" from public..."
        );

        try {
            $target_absolute_path = ProjectPath::public($target_relative_path);

            $this->ensureSymlinkDirectoryHierarchyAtPublic(
                $link_absolute_path = ProjectPath::public() . DIRECTORY_SEPARATOR . $link_relative_path
            );

            DirectoryFiles::createSymbolicLink($link_relative_path, $target_relative_path, ProjectPath::public());
        } catch (FailedToCreateSymlink
        |FailedToEnsureSymlinkDirectoryHierarchyAtPublic
        |FailedToTravelDirectories
        |PathNotFoundException $exception) {
            throw new FailedToCreatePublicSymlink($link_relative_path, $target_relative_path, $exception);
        }

        $this->writelnSuccessWhenVerbose(
            "\"$link_absolute_path\" pointing to \"$target_absolute_path\" created."
        );
    }

    /**
     * @param string $link_absolute_path
     * @return void
     * @throws FailedToEnsureSymlinkDirectoryHierarchyAtPublic
     */
    private function ensureSymlinkDirectoryHierarchyAtPublic(string $link_absolute_path): void
    {
        $link_absolute_parent_path = dirname($link_absolute_path);

        if (!is_dir($link_absolute_parent_path) && !is_link($link_absolute_parent_path)) {
            try {
                DirectoryFiles::createDirectoryAt($link_absolute_parent_path);
            } catch (FailedToCreateDirectory|FailedToGetDirectoryPermissions|PathNotFoundException $exception) {
                throw new FailedToEnsureSymlinkDirectoryHierarchyAtPublic($link_absolute_parent_path, $exception);
            }
        }
    }
}

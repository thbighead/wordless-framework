<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToCreateSymlink;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\FailedToGetFileContent;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Services\PublicSymlink;
use Wordless\Services\PublicSymlink\Exceptions\InvalidPublicSymlinkTargetWithExceptions;
use Wordless\Services\PublicSymlinksResolver;
use Wordless\Services\Wlsymlinks\Exceptions\EmptyWlsymlinks;

class GeneratePublicWordpressSymbolicLinks extends ConsoleCommand
{
    public const COMMAND_NAME = 'wordless:symlinks';

    protected static $defaultName = self::COMMAND_NAME;

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
        return 'Reading config/wp-symlinks.php this script creates symbolic links to allow or not direct access through HTTP.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws EmptyWlsymlinks
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeletePath
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToGetFileContent
     * @throws InvalidDirectory
     * @throws InvalidPublicSymlinkTargetWithExceptions
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Removing old symlinks...', function () {
            DirectoryFiles::recursiveRemoveSymbolicLinks(ProjectPath::public());
            DirectoryFiles::recursiveDeleteEmptyDirectories(ProjectPath::public(), false);
        });

        $this->wrapScriptWithMessages('Generating public symbolic links...', function () {
            $symlinks_configured = Config::tryToGetOrDefault('public-symlinks', []);
            $publicSymlinkResolver = new PublicSymlinksResolver;

            foreach ($symlinks_configured as $link_relative_path => $target_relative_path) {
                $publicSymlinkResolver->addSymlink(new PublicSymlink($link_relative_path, $target_relative_path));
            }

            foreach ($publicSymlinkResolver->retrieveCleanedSymlinks() as $link_relative_path_from_public => $target_relative_path_from_public) {
                $this->createPublicSymlink($link_relative_path_from_public, $target_relative_path_from_public);
            }
        });

        return Command::SUCCESS;
    }

    /**
     * @param string $link_relative_path
     * @param string $target_relative_path
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     * @throws FailedToCreateSymlink
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     */
    private function createPublicSymlink(string $link_relative_path, string $target_relative_path)
    {
        $this->writelnInfoWhenVerbose(
            "Creating \"$link_relative_path\" from public pointing to \"$target_relative_path\" from public..."
        );

        $target_absolute_path = ProjectPath::public($target_relative_path);

        $this->ensureSymlinkDirectoryHierarchyAtPublic(
            $link_absolute_path = ProjectPath::public() . DIRECTORY_SEPARATOR . $link_relative_path
        );

        DirectoryFiles::createSymlink($link_relative_path, $target_relative_path, ProjectPath::public());

        $this->writelnSuccessWhenVerbose(
            "\"$link_absolute_path\" pointing to \"$target_absolute_path\" created."
        );
    }

    /**
     * @param string $link_absolute_path
     * @return void
     * @throws PathNotFoundException
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     */
    private function ensureSymlinkDirectoryHierarchyAtPublic(string $link_absolute_path)
    {
        $link_absolute_parent_path = dirname($link_absolute_path);

        if (!is_dir($link_absolute_parent_path) && !is_link($link_absolute_parent_path)) {
            DirectoryFiles::createDirectoryAt($link_absolute_parent_path);
        }
    }
}

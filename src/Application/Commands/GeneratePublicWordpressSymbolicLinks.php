<?php

namespace Wordless\Application\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCreateSymlink;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class GeneratePublicWordpressSymbolicLinks extends ConsoleCommand
{
    final public const COMMAND_NAME = 'wordless:symlinks';
    private const FILTER_RULE = '!';
    private const SLASH = '/';

    private array $symlinks = [];

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
        return 'Reading config/wp-symlinks.php this script creates symbolic links to allow or not direct access through HTTP.';
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
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeletePath
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Removing old symlinks...', function () {
            DirectoryFiles::recursiveRemoveSymbolicLinks(ProjectPath::public());
        });

        $this->wrapScriptWithMessages('Generating public symbolic links...', function () {
            foreach ($this->getMappedSymlinks() as $raw_link_name => $raw_target) {
                $this->parseSymlink($raw_link_name, $raw_target);
            }

            foreach ($this->symlinks as $link_name => $target) {
                $this->generateSymbolicLink($target, $link_name);
            }
        });

        return Command::SUCCESS;
    }

    /**
     * @param string $raw_link_name
     * @param string $raw_target
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function parseSymlink(string $raw_link_name, string $raw_target): void
    {
        $target = $this->parseTarget($raw_target);

        if (is_string($target)) {
            $this->symlinks[$this->parseLinkName($raw_link_name)] = $target;
            return;
        }

        foreach ($target as $subtarget) {
            $this->symlinks[$this->parseLinkName(
                $raw_link_name,
                Str::afterLast($subtarget, self::SLASH)
            )] = $subtarget;
        }
    }

    /**
     * @param string $target
     * @param string $link_name
     * @return void
     * @throws FailedToCreateSymlink
     * @throws PathNotFoundException
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     */
    private function generateSymbolicLink(string $target, string $link_name): void
    {
        DirectoryFiles::changeWorkingDirectoryDoAndGoBack('public', function () use ($link_name, $target) {
            DirectoryFiles::createSymbolicLink($link_name, $target);
        });
    }

    /**
     * @return array
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    private function getMappedSymlinks(): array
    {
        return Config::get('wp-symlinks');
    }

    /**
     * @param string $link_name
     * @param string $target
     * @return string
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function parseLinkName(string $link_name, string $target = ''): string
    {
        $link_name = trim($link_name, self::SLASH);
        $link_name = empty($target) ? $link_name : trim("$link_name/$target", self::SLASH);
        $link_name_relative_path = Str::beforeLast($link_name, self::SLASH);

        if ($link_name_relative_path === $link_name) {
            return $link_name;
        }

        $permissions = DirectoryFiles::getPermissions($public_path = ProjectPath::public());

        $link_name_full_path = "$public_path/$link_name_relative_path";

        if (is_dir($link_name_full_path)) {
            $this->writelnCommentWhenVerbose("Directory $link_name_full_path already created, skipping.");

            return $link_name;
        }

        DirectoryFiles::createDirectoryAt($link_name_full_path, $permissions);

        return $link_name;
    }

    /**
     * @param string $target
     * @return string[]|string
     * @throws PathNotFoundException
     * @throws InvalidDirectory
     */
    private function parseTarget(string $target): array|string
    {
        if (!Str::contains($target, self::FILTER_RULE)) {
            return trim($target, self::SLASH);
        }

        [$target, $exceptions] = explode(self::FILTER_RULE, $target);

        if (Str::contains($exceptions, self::SLASH)) {
            throw new InvalidArgumentException(
                'The following target exceptions are invalid because they have one or more "'
                . self::SLASH
                . "\": $exceptions"
            );
        }

        $targets = [];

        foreach (DirectoryFiles::listFromDirectory(
            ProjectPath::public($target),
            explode(',', $exceptions)
        ) as $filtered_target_subpaths) {
            $targets[] = trim($target, self::SLASH) . self::SLASH . $filtered_target_subpaths;
        }

        return $targets;
    }
}

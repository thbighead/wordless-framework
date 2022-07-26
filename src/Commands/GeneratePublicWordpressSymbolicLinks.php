<?php

namespace Wordless\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToCreateSymlink;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class GeneratePublicWordpressSymbolicLinks extends WordlessCommand
{
    public const COMMAND_NAME = 'wordless:symlinks';
    private const FILTER_RULE = '!';
    private const SLASH = '/';
    protected static $defaultName = self::COMMAND_NAME;

    private array $symlinks = [];

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
     * @throws FailedToCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     * @throws FailedToDeletePath
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Removing old symlinks...', function () {
            DirectoryFiles::recursiveDelete(ProjectPath::public(), ['.gitignore' => '.gitignore'], false);
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
    private function parseSymlink(string $raw_link_name, string $raw_target)
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
     */
    private function generateSymbolicLink(string $target, string $link_name)
    {
        $command = "cd public && ln -s -r $target $link_name";

        $this->writelnWhenVerbose("Creating \"$link_name\" pointing to \"$target\" with \"$command\" command.");

        if ($this->executeCommand($command) !== self::SUCCESS) {
            throw new FailedToCreateSymlink($command);
        }
    }

    /**
     * @return mixed
     * @throws PathNotFoundException
     */
    private function getMappedSymlinks()
    {
        return include ProjectPath::config('wp-symlinks.php');
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

        if (($permissions = fileperms($public_path = ProjectPath::public())) === false) {
            throw new FailedToGetDirectoryPermissions($public_path);
        }

        $link_name_full_path = "$public_path/$link_name_relative_path";

        if (is_dir($link_name_full_path)) {
            $this->writelnWhenVerbose("Directory $link_name_full_path already created, skipping.");
            return $link_name;
        }

        DirectoryFiles::createDirectoryAt($link_name_full_path, $permissions);

        return $link_name;
    }

    /**
     * @param string $target
     * @return array|string
     * @throws PathNotFoundException
     * @throws InvalidDirectory
     */
    private function parseTarget(string $target)
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
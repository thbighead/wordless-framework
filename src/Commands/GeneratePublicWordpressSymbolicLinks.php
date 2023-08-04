<?php

namespace Wordless\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToCreateSymlink;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class GeneratePublicWordpressSymbolicLinks extends ConsoleCommand
{
    public const COMMAND_NAME = 'wordless:symlinks';
    private const EXCEPT_MARKER = '!';
    private const SLASH = '/';
    private const WLSYMLINKS_FILENAME = '.wlsymlinks';

    protected static $defaultName = self::COMMAND_NAME;

    private array $symlinks = [];
    private array $exceptions = [];

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
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToGetFileContent
     * @throws InvalidConfigKey
     * @throws InvalidDirectory
     * @throws InvalidSymlinkTargetException
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Removing old symlinks...', function () {
            DirectoryFiles::recursiveRemoveSymbolicLinks(ProjectPath::public());
            DirectoryFiles::recursiveDeleteEmptyDirectories(ProjectPath::public(), false);
        });

        $this->wrapScriptWithMessages('Generating public symbolic links...', function () {
            $this->loadMappedSymlinks()
                ->resolveTargetsExceptions()
                ->resolveWlsymlinks()
                ->cleanSymlinksPointingToDirectoriesWithWlsymlinks()
                ->generateSymbolicLinks();
        });

        return Command::SUCCESS;
    }

    /**
     * @param string $exceptions_string
     * @param string $target_without_exceptions
     * @return string[]
     */
    private function addToExceptions(string $exceptions_string, string $target_without_exceptions): array
    {
        $this->exceptions[$target_without_exceptions] = [];
        $exceptions = explode(',', $exceptions_string);

        foreach ($exceptions as $exception) {
            $this->exceptions[$target_without_exceptions][] = $exception;
        }

        return $exceptions;
    }

    /**
     * @return GeneratePublicWordpressSymbolicLinks
     * @throws PathNotFoundException
     */
    private function cleanSymlinksPointingToDirectoriesWithWlsymlinks(): GeneratePublicWordpressSymbolicLinks
    {
        $cleaned_symlinks = [];

        foreach ($this->symlinks as $link_name => $target) {
            if ($this->isWlsymlinkInsidePath($this->targetRealpath($target))) {
                continue;
            }

            $cleaned_symlinks[$link_name] = $target;
        }

        $this->symlinks = $cleaned_symlinks;

        return $this;
    }

    /**
     * @param string[] $directory_paths_list
     * @return string[]
     * @throws FailedToGetFileContent
     */
    private function cleanWlsymlinksDirectoryPathsList(array $directory_paths_list): array
    {
        $initial_paths_count = count($directory_paths_list);

        for ($i = 0; $i < $initial_paths_count - 1; $i++) {
            if (!isset($directory_paths_list[$i])) {
                continue;
            }

            for ($j = $i + 1; $j < $initial_paths_count; $j++) {
                if (!isset($directory_paths_list[$j])) {
                    continue;
                }

                if (Str::beginsWith($directory_paths_list[$j], $directory_paths_list[$i])) {
                    $should_clean = true;

                    foreach ($this->getWlsymlinksContentLines($directory_paths_list[$i]) as $relative_path) {
                        if (Str::beginsWith(
                            $this->trimSlashes(Str::after($directory_paths_list[$j], $directory_paths_list[$i])),
                            $relative_path
                        )) {
                            $should_clean = false;
                            break;
                        }
                    }

                    if ($should_clean) {
                        unset($directory_paths_list[$j]);
                    }
                }
            }
        }

        return array_values($directory_paths_list);
    }

    /**
     * @param string $link_name
     * @return void
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     * @throws FailedToCreateDirectory
     */
    private function generateLinkPath(string $link_name): void
    {
        $link_name = $this->trimSlashes($link_name);
        $link_name_directory_relative_path = Str::beforeLast($link_name, self::SLASH);

        if ($link_name_directory_relative_path === $link_name) {
            return;
        }

        if (($permissions = fileperms($public_path = ProjectPath::public())) === false) {
            throw new FailedToGetDirectoryPermissions($public_path);
        }

        $link_name_directory_full_path = "$public_path/$link_name_directory_relative_path";

        if (is_dir($link_name_directory_full_path)) {
            $this->writelnCommentWhenVerbose("Directory $link_name_directory_full_path already created, skipping.");

            return;
        }

        DirectoryFiles::createDirectoryAt($link_name_directory_full_path, $permissions);
    }

    /**
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    private function generateSymbolicLinks()
    {
        foreach ($this->symlinks as $link_name => $target) {
            $this->generateLinkPath($link_name);

            $command = "cd public && ln -s -r $target $link_name";

            $this->writelnInfoWhenVerbose("Creating \"$link_name\" pointing to \"$target\" with \"$command\" command.");

            if ($this->executeCommand($command) !== self::SUCCESS) {
                throw new FailedToCreateSymlink($command);
            }
        }
    }

    /**
     * @return string[]
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     */
    private function getMappedSymlinks(): array
    {
        return Config::get('wp-symlinks');
    }

    /**
     * @param string $wlsymlinks_absolute_path
     * @return array
     * @throws FailedToGetFileContent
     */
    private function getWlsymlinksContentLines(string $wlsymlinks_absolute_path): array
    {
        $wlsymlinks_filepath = "$wlsymlinks_absolute_path/" . self::WLSYMLINKS_FILENAME;

        if (($wlsymlinks_content = file_get_contents($wlsymlinks_filepath)) === false) {
            throw new FailedToGetFileContent($wlsymlinks_filepath);
        }

        return array_filter(explode(PHP_EOL, $wlsymlinks_content));
    }

    /**
     * @return $this
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    private function loadMappedSymlinks(): GeneratePublicWordpressSymbolicLinks
    {
        $this->symlinks = $this->getMappedSymlinks();

        return $this;
    }

    /**
     * @param string[] $resolved_symlinks
     * @param string $original_link_name
     * @param string $original_target
     * @return void
     * @throws InvalidDirectory
     * @throws InvalidSymlinkTargetException
     * @throws PathNotFoundException
     */
    private function mountSymlinksToExceptInto(
        array  &$resolved_symlinks,
        string $original_link_name,
        string $original_target
    )
    {
        [$target_without_exceptions, $exceptions_string] = $this->splitTargetFromExceptions($original_target);

        foreach (DirectoryFiles::listFromDirectory(
            $this->targetRealpath($target_without_exceptions),
            $this->addToExceptions($exceptions_string, $target_without_exceptions)
        ) as $filtered_target_relative_subpath) {
            $resolved_symlinks["$original_link_name/$filtered_target_relative_subpath"] =
                "{$this->trimSlashes($target_without_exceptions)}/$filtered_target_relative_subpath";
        }
    }

    /**
     * @param string $directory_path
     * @return string[]
     * @throws FailedToGetFileContent
     * @throws PathNotFoundException
     */
    private function recursiveSearchDirectoriesWithWlsymlinks(string $directory_path): array
    {
        $absolute_directory_paths_found = [];

        foreach (DirectoryFiles::recursiveRead($directory_path) as $absolute_filepath) {
            if (basename($absolute_filepath) === self::WLSYMLINKS_FILENAME) {
                $wlsymlinks_absolute_directory_path = dirname($absolute_filepath);
                $absolute_directory_paths_found[$wlsymlinks_absolute_directory_path] =
                    $wlsymlinks_absolute_directory_path;
            }
        }

        usort($absolute_directory_paths_found, function ($a, $b): int {
            return Str::countSubstring($a, self::SLASH) - Str::countSubstring($b, self::SLASH);
        });

        return $this->cleanWlsymlinksDirectoryPathsList($absolute_directory_paths_found);
    }

    /**
     * @return GeneratePublicWordpressSymbolicLinks
     * @throws InvalidDirectory
     * @throws InvalidSymlinkTargetException
     * @throws PathNotFoundException
     */
    private function resolveTargetsExceptions(): GeneratePublicWordpressSymbolicLinks
    {
        $resolved_symlinks = [];

        foreach ($this->symlinks as $link_name => $target) {
            if (!$this->targetHasExceptions($target)) {
                $resolved_symlinks[$link_name] = $this->trimSlashes($target);
                continue;
            }

            $this->mountSymlinksToExceptInto($resolved_symlinks, $link_name, $target);
        }

        $this->symlinks = $resolved_symlinks;

        return $this;
    }

    /**
     * @return GeneratePublicWordpressSymbolicLinks
     * @throws FailedToGetFileContent
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function resolveWlsymlinks(): GeneratePublicWordpressSymbolicLinks
    {
        $resolved_symlinks = [];

        foreach ($this->symlinks as $link_name => $target) {
            if (!is_dir($target_realpath = $this->targetRealpath($target))) {
                $resolved_symlinks[$link_name] = $this->trimSlashes($target);
                continue;
            }

            if (empty($wlsymlinks_absolute_directory_paths =
                $this->recursiveSearchDirectoriesWithWlsymlinks($target_realpath))) {
                $resolved_symlinks[$link_name] = $this->trimSlashes($target);

                continue;
            }

            $this->pushPathsGeneratedByWlsymlinksParsingInto(
                $resolved_symlinks,
                $this->parseWlsymlinksPathsToIncludeInto(
                    $resolved_symlinks,
                    $wlsymlinks_absolute_directory_paths,
                    $link_name,
                    $target
                ),
                $link_name,
                $target
            );
        }

        $this->symlinks = $resolved_symlinks;

        return $this;
    }

    /**
     * @param string[] $symlinks
     * @param string[] $wlsymlinks_absolute_directory_paths
     * @param string $original_link_name
     * @param string $original_target
     * @return string[]
     * @throws FailedToGetFileContent
     * @throws PathNotFoundException
     */
    private function parseWlsymlinksPathsToIncludeInto(
        array  &$symlinks,
        array  $wlsymlinks_absolute_directory_paths,
        string $original_link_name,
        string $original_target
    ): array
    {
        $generated_paths_to_include = [];

        for ($i = count($wlsymlinks_absolute_directory_paths) - 1; $i >= 0; $i--) {
            foreach ($this->getWlsymlinksContentLines(
                $wlsymlinks_absolute_directory_paths[$i]
            ) as $symlinkable_relative_path) {
                $symlinkable_absolute_path = ProjectPath::realpath(
                    "$wlsymlinks_absolute_directory_paths[$i]/$symlinkable_relative_path"
                );

                if ($this->isWlsymlinkInsidePath($symlinkable_absolute_path)) {
                    continue;
                }

                $target_relative_subpath = $this->trimSlashes(Str::after(
                    $symlinkable_absolute_path,
                    $this->targetRealpath($original_target)
                ));
                $symlinks["$original_link_name/$target_relative_subpath"] =
                    "$original_target/$target_relative_subpath";
                $path_to_include = dirname($wlsymlinks_absolute_directory_paths[$i]);
                $generated_paths_to_include[$path_to_include] = $path_to_include;
            }
        }

        return $generated_paths_to_include;
    }

    /**
     * @param string[] $symlinks
     * @param string[] $paths_to_include
     * @param string $original_link_name
     * @param string $original_target
     * @return void
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function pushPathsGeneratedByWlsymlinksParsingInto(
        array  &$symlinks,
        array  $paths_to_include,
        string $original_link_name,
        string $original_target
    )
    {
        foreach ($paths_to_include as $absolute_path) {
            if ($this->isWlsymlinkInsidePath($absolute_path)) {
                continue;
            }

            foreach (DirectoryFiles::listFromDirectory($absolute_path) as $relative_path) {
                if ($this->isGeneratedPathAnException("$absolute_path/$relative_path")) {
                    continue;
                }

                $base_target = Str::beforeLast($original_target, self::SLASH);
                $base_link_name = Str::beforeLast($original_link_name, self::SLASH);

                $symlinks["$base_link_name/$relative_path"] = "$base_target/$relative_path";
            }
        }
    }

    private function isWlsymlinkInsidePath(string $path): bool
    {
        try {
            ProjectPath::realpath("$path/" . self::WLSYMLINKS_FILENAME);
            return true;
        } catch (PathNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @param string $path_to_include
     * @return bool
     * @throws PathNotFoundException
     */
    private function isGeneratedPathAnException(string $path_to_include): bool
    {
        foreach ($this->exceptions as $base_target => $relative_filepaths) {
            foreach ($relative_filepaths as $relative_filepath) {
                if ($path_to_include === $this->targetRealpath("$base_target/$relative_filepath")) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $target_with_exceptions
     * @return string[]
     * @throws InvalidSymlinkTargetException
     */
    private function splitTargetFromExceptions(string $target_with_exceptions): array
    {
        [$target_without_exceptions, $exceptions] =
            explode(self::EXCEPT_MARKER, $target_with_exceptions);

        if (Str::contains($exceptions, self::SLASH)) {
            throw new InvalidSymlinkTargetException(
                'The following target exceptions are invalid because they have one or more "'
                . self::SLASH
                . "\": $exceptions"
            );
        }

        return [$target_without_exceptions, $exceptions];
    }

    private function targetHasExceptions(string $target): bool
    {
        return Str::contains($target, self::EXCEPT_MARKER);
    }

    /**
     * @param string $target_relative_path
     * @return string
     * @throws PathNotFoundException
     */
    private function targetRealpath(string $target_relative_path): string
    {
        return ProjectPath::public($target_relative_path);
    }

    private function trimSlashes(string $path): string
    {
        return trim($path, self::SLASH);
    }
}
